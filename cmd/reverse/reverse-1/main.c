#include <stdio.h>
#include <stdlib.h>
#include <string.h>

char *getflag(char *yourName)
{
        char name[25];
        char filePath[29];

        strcpy(filePath, "/assets/nonexistant-flag.txt");
        strcpy(name, yourName);

        printf("%s\n", filePath);
        fflush(stdout);

        if (strstr(filePath, "/assets/flag.txt")) {
                char *flag = "FLAG-gTwJDjCGPFfEruCQFs8z";
                return flag;
        }

        char *newName = malloc(sizeof(char*) * sizeof(name));
        strcpy(newName, name);
        return newName;
}