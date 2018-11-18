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
    
    /*dyad_writef(e->stream, "argc: %d\n", argc);
    for (i = 0; i < argc; i++)
    {
        dyad_writef(e->stream, "argv[%d] = \"%s\"\n", i, argv[i]);
    }*/
    
    for (i = 0; i < sizeof(commands) / sizeof(struct command_t); i++) {
        if (strcmp(argv[0], commands[i].name) == 0)
            return commands[i].func(e->stream, argc, argv);
    }
}

void cmd_help(dyad_Stream *stream, int argc, char *argv[])
{
    dyad_writef(stream, 
        "-=-=-=-China Town DVR v1.03-=-=-=-\n"
        "Available Commands:\n"
    );
    
    int i;
    for (i = 0; i < sizeof(commands) / sizeof(struct command_t); i++) {
        if (commands[i].authenticated && !connection_authenticated)
            continue;
        dyad_writef(stream, " %s", commands[i].name);
    }
    
    dyad_writef(stream, "\n");
}