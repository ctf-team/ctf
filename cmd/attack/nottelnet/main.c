#include <stdlib.h>
#include <stdio.h>
#include <stdint.h>
#include <string.h>
#include <unistd.h>
#include <limits.h>
#include <libgen.h>

#include "includes/dyad.h"
#include "includes/cmd.h"

dyad_Stream *server_stream = NULL;

static void onAccept(dyad_Event *e) 
{
    printf("Accepted new connection\n");
    if (fork() == 0) {
        dyad_close(server_stream);
        dyad_addListener(e->remote, DYAD_EVENT_LINE, handleCommand, NULL);
        
        dyad_writef(e->remote, "$ ");
        
        while (dyad_getStreamCount() > 0) {
            dyad_update();
        }
        
        printf("%s\n", "Connection closed.");
        
        _exit(0);
    }
    dyad_close(e->remote);
}


int main()
{
    // get the flag into our memory and clear env after
    /*char *flag = strcpy(getenv("FLAG"));
    memset(getenv("FLAG"), 0, strlen(getenv("FLAG")));*/
    
    if (fork() > 0) {
        for (;;) {
            int status;
            pid_t killed_pid;
            while ((killed_pid = waitpid(-1, &status, 0)) > 0) {}
        }
        
        _exit(0);
    }
    
    char wherearewe[PATH_MAX];
    readlink("/proc/self/exe", wherearewe, PATH_MAX);
    chdir(dirname(wherearewe));
    
    dyad_init();

    server_stream = dyad_newStream();
    dyad_addListener(server_stream, DYAD_EVENT_ACCEPT, onAccept, NULL);
    dyad_listen(server_stream, 8000);
    
    while (dyad_getStreamCount() > 0) {
        dyad_update();
    }
    
    dyad_shutdown();
    return 0;
}