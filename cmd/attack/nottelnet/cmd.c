#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>


#include "includes/cmd.h"

int connection_authenticated = 0;
void handleCommand(dyad_Event *e) 
{
    // parse commands here.
    int i;
    char *argv[20] = {0};
    int argc = 0;
    
    // trim new line from command
    e->data[strcspn(e->data, "\n")] = 0;
    
    if (strlen(e->data) == 0)
        return;
    
    char *string = e->data;
    char *found = NULL;
    
    while((found = strsep(&string," ")) != NULL && argc < 20)
    {
        argv[argc] = strdup(found);
        argc++;
    }
    
    /*
    dyad_writef(e->stream, "argc: %d\n", argc);
    for (i = 0; i < argc; i++)
    {
        dyad_writef(e->stream, "argv[%d] = \"%s\"\n", i, argv[i]);
    }
    */
    
    for (i = 0; i < sizeof(commands) / sizeof(struct command_t); i++) {
        if (strcmp(argv[0], commands[i].name) == 0 && connection_authenticated >= commands[i].authenticated) {
            commands[i].func(e->stream, argc, argv);
            dyad_writef(e->stream, "$ ");
            return;
        }
    }
    
    // Command not found.
    dyad_writef(e->stream, "Command not found.\n$ ");
}
