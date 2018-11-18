#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <unistd.h>
#include <time.h>
#include <libgen.h>
#include <dirent.h>

#include "includes/cmd.h"

void record_help(dyad_Stream *stream) 
{
    dyad_writef(stream, 
        "Available usage:\n"
        "record -h\n"
        "    - Displays this message.\n"
        "record start <filename>\n" // not vuln
        "    - Starts a new DVR recording based on filename.\n"
        "record stop <filename>\n" // not vuln
        "    - Stops a new DVR recording based on filename.\n"
        "record delete <filename>\n" // not vuln
        "    - Deletes a DVR recording.\n"
        "record info <filename>\n" // vuln
        "    - Displays the information for a specific recording.\n"
        "record list [optional path]\n" // vuln
        "    - Displays previous recordings in the given directory.\n"
    );
}

void record_start(dyad_Stream *stream, int argc, char *argv[]) 
{
    // Write to the specified file IF it does not exist.
    if (argc != 3) 
    {
        dyad_writef(stream, "Invalid Arguments (check -h)\n");
        return;
    }

    char *file = basename(argv[2]);
    char *path = malloc(strlen(file) + 12);
    sprintf(path, "recordings/%s", file);

    if (access(path, F_OK) != -1)
    {
        free(path);
        dyad_writef(stream, "Can't start recording as recording already exists!\n");
        return;
    }
    
    FILE *fp = fopen(path, "w");
    if (!fp)
    {
        free(path);
        dyad_writef(stream, "Failed to open recording!\n");
        return;
    }
    
    free(path);
    
    time_t current_time = time(NULL);
    
    fprintf(fp, "Recording started at %s", ctime(&current_time));
    
    fclose(fp);
    
    dyad_writef(stream, "Recording started at %s", ctime(&current_time));
}

void record_stop(dyad_Stream *stream, int argc, char *argv[]) 
{
    // Write to the specified file IF it exists and it's not named basename("flag.txt")
    if (argc != 3)
    {
        dyad_writef(stream, "Invalid Arguments (check -h)\n");
        return;
    }
    
    char *file = basename(argv[2]);
    
    char *path = malloc(strlen(file) + 12);
    sprintf(path, "recordings/%s", file);
    
    if (access(path, F_OK) == -1)
    {
        free(path);
        dyad_writef(stream, "Can't stop recording as recording does not exist!\n");
        return;
    }

    FILE *fp = fopen(path, "a+");
    if (!fp)
    {
        free(path);
        dyad_writef(stream, "Failed to stop recording!\n");
        return;
    }
    free(path);
    
    time_t current_time = time(NULL);
    fprintf(fp, "Recording stopped at %s", ctime(&current_time));
    fclose(fp);
    
    dyad_writef(stream, "Recording stopped at %s", ctime(&current_time));
}

void record_info(dyad_Stream *stream, int argc, char *argv[]) 
{
    // Read from the specified file
    if (argc != 3)
    {
        dyad_writef(stream, "Invalid Arguments (check -h)\n");
        return;
    }
    
    char *path = path = malloc(strlen(argv[2]) + 12);
    sprintf(path, "recordings/%s", argv[2]);
    
    FILE *fp = fopen(path, "r");
    free(path);
    
    if (!fp)
    {
        dyad_writef(stream, "Can't open specified recording.\n");
        return;
    }
    
    dyad_writef(stream, "Recording information:\n");
    char buf[1024];
    int got = 0;
    while((got = fread(buf, 1, 1024, fp)) > 0)
    {
        dyad_write(stream, buf, got);
    }
    
    fclose(fp);
    
    dyad_writef(stream, "\n");
}


void record_delete(dyad_Stream *stream, int argc, char *argv[])
{
    // Delete file IF exists and not named basename(flag.txt)
    if (argc != 3) 
    {
        dyad_writef(stream, "Invalid Arguments (check -h)\n");
        return;
    }
    
    char *file = basename(argv[2]);
    char *path = malloc(strlen(file) + 12);
    sprintf(path, "recordings/%s", file);
    
    
    if (access(path, F_OK) == -1)
    {
        free(path);
        dyad_writef(stream, "Can't delete recording as recording does not exist!\n");
        return;
    }
    
    if (unlink(path) != 0)
    {
        dyad_writef(stream, "Failed to delete the recording %s!\n", file);
    }
    
    free(path);
    
    dyad_writef(stream, "Deleted recording %s!\n", file);
    
}

void record_list(dyad_Stream *stream, int argc, char *argv[])
{
    char *path = "recordings/";
    if (argc > 2)
    {
        path = malloc(strlen(argv[2]) + 12);
        sprintf(path, "recordings/%s", argv[2]);
    }
    
    struct dirent *de; 
    DIR *dr = opendir(path); 
    if (argc > 2)
    {
        free(path);
    }
    
    if (dr == NULL)
    { 
        dyad_writef(stream, "Could not open the specified recording directory!\n"); 
        return; 
    }
    
    dyad_writef(stream, "Recordings:\n");
    while ((de = readdir(dr)) != NULL) 
    {
        if (strcmp(de->d_name, ".") == 0 || strcmp(de->d_name, "..") == 0)
            continue;
            
        dyad_writef(stream, " %s", de->d_name);
    }
    
    dyad_writef(stream, "\n");
}

void cmd_record(dyad_Stream *stream, int argc, char *argv[]) 
{
    // parse args here.
    if (argc < 2)
    {
        dyad_writef(stream, "Invalid arguments (check -h)\n");
        return;
    }
    
    if (strcmp(argv[1], "-h") == 0)
    {
        return record_help(stream);
    }
    
    if (strcmp(argv[1], "start") == 0) {
        return record_start(stream, argc, argv);
    }
    
    if (strcmp(argv[1], "stop") == 0) {
        return record_stop(stream, argc, argv);
    }
    
    if (strcmp(argv[1], "list") == 0) {
        return record_list(stream, argc, argv);
    }
    
    if (strcmp(argv[1], "info") == 0) {
        return record_info(stream, argc, argv);
    }
    
    if (strcmp(argv[1], "delete") == 0) {
        return record_delete(stream, argc, argv);
    }
    
    dyad_writef(stream, "Invalid arguments (check -h)\n");
}