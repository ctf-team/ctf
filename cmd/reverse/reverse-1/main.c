#include <stdio.h>
#include <stdlib.h>
#include <string.h>

char *getflag(char *yourName)
{
        char name[1024];
        char filePath[1024];

        strcpy(filePath, "/assets/nonexistant-flag.txt");
        strcpy(name, yourName);

        printf("%s\n", filePath);
        fflush(stdout);

        if (strstr(filePath, "/assets/flag.txt")) {
                char *flag = getenv("reverse1_flag");
                return flag;
        }

        char *newName = malloc(sizeof(char*) * sizeof(name));
        strcpy(newName, name);
        return newName;
}