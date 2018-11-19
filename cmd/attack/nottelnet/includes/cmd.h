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

// cmd_help.c
void cmd_help(dyad_Stream *stream, int argc, char *argv[]);

// cmd_record.c
void cmd_record(dyad_Stream *stream, int argc, char *argv[]);

// cmd_login.c
void cmd_login(dyad_Stream *stream, int argc, char *argv[]);

// cmd_utils.c
void cmd_mac_address(dyad_Stream *stream, int argc, char *argv[]);
void cmd_ip_address(dyad_Stream *stream, int argc, char *argv[]);
void cmd_hostname(dyad_Stream *stream, int argc, char *argv[]);



static struct command_t commands[] = {
    {"help", 0, cmd_help},
    {"record", 0, cmd_record},
    {"login", 0, cmd_login},
    {"mac", 1, cmd_mac_address},
    {"ip", 1, cmd_ip_address},
    {"hostname", 1, cmd_hostname}
};


#endif