#include <stdlib.h>

#include "includes/cmd.h"

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