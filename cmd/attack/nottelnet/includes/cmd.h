#ifndef CMD_H
#define CMD_H

#include "dyad.h"

void handleCommand(dyad_Event *e);

struct command_t {
    char *name;
    int authenticated;
    void (*func)(dyad_Stream *, int, char *[]);
};
extern int connection_authenticated;

// command_help.c
void cmd_help(dyad_Stream *stream, int argc, char *argv[]);


// command_record.c
void cmd_record(dyad_Stream *stream, int argc, char *argv[]);


static struct command_t commands[] = {
    {"help", 0, cmd_help},
    {"record", 0, cmd_record},
};


#endif