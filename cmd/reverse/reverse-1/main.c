#include <stdio.h>
#include <stdlib.h>
#include <string.h>

char *getflag(char *yourName)
{
        char name[512];
        char filePath[512];
        char *ptr;

        const char *nonexistantFilePath = "/assets/nonexistant-flag.txt";
        const char *flagPath = "/assets/flag.txt";

        strcpy(filePath, nonexistantFilePath);
        strcpy(name, yourName);

        if( (ptr = strchr(filePath, '\n')) != NULL) {
            *ptr = '\0';
        }

        if (strcmp(filePath, flagPath) == 0) {
                char *flag = getenv("reverse1_flag");
                return flag;
        }

        char *newName = malloc(sizeof(char*) * strlen(name));
        strcpy(newName, name);
        return newName;
}
