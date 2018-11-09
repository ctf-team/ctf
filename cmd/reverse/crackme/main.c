#include <stdio.h>
#include <string.h>
#include <stdlib.h>

char *getLine()
{
    char *line = malloc(sizeof(char*) * 1024);
    memset(line, 0, sizeof(char*) * 1024);
    fgets(line, 1024, stdin);

    return line;
}

int main(int argc, char *argv[]) {
    while(1) {
        printf("Guess the flag!: ");
        char *flag = strtok(getLine(), "\n");
        int cmp = strcmp(flag, "FLAG-r4eIj5MvkYFaPEXGtdlg");
        free(flag);
        if(cmp == 0) {
            printf("You guessed the flag correctly!\n");
            break;
        }
        printf("Wrong!\n");
    }
}