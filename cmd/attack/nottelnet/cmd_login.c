#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <unistd.h>
#include <libgen.h>
#include <dirent.h>

#include "includes/md5.h"
#include "includes/cmd.h"

static void dahua_compress(unsigned char *in, unsigned char *out)
{
	int i, j;

	for (i = 0, j = 0; i < 16; i += 2, j++) {
		out[j] = (in[i] + in[i+1]) % 62;

		if (out[j] < 10) {
			out[j] += 48;
		} else if (out[j] < 36) {
			out[j] += 55;
		} else {
			out[j] += 61;
		}
	}
}

void login_help(dyad_Stream *stream)
{
    dyad_writef(stream,
        "Available usage:\n"
        "login <username> <password>\n"
        "    - Authenticate as a user.\n"
    );
}

void login_auth(dyad_Stream *stream, int argc, char *argv[])
{
    if (argc != 3) 
    {
        dyad_writef(stream, "Invalid Arguments (see -h)\n");
        return;
    }
    
    // read from our auth.bin file and look for passed creds.
    FILE *fp = fopen("auth.bin", "r");
    
    if (!fp)
    {
        dyad_writef(stream, "Can't read the auth file!.\n");
        return;
    }
    
    // auth file shouldn't be larger than 1024, truncate at that.
    char buf[1024];
    fread(buf, 1, 1024, fp);
    fclose(fp);
    
    char *users[20] = {0};
    int users_count = 0;
    
    // break up by newline and spaces.
    char *split = strtok(buf, "\n");
    while (split != NULL) 
    {
        if(split[0] == '#')
        {
            split = strtok(NULL, "\n");
            continue;
        }
        
        char *found = NULL;
        while((found = strsep(&split,":")) != NULL && users_count < 20)
        {
            users[users_count] = strdup(found);
            users_count++;
        }
        split = strtok(NULL, "\n");
    }
    
    char dahua_hash[16];
    
    MD5_CTX dahua_step1;
    MD5_Init(&dahua_step1);
    MD5_Update(&dahua_step1, argv[2], strlen(argv[2]));
    MD5_Final(dahua_hash, &dahua_step1);
    dahua_compress(dahua_hash, dahua_hash);
    dahua_hash[8] = 0;
    
    printf("attempted login: \"%s:%s\" - hash: \"%s\"\n", argv[1], argv[2], dahua_hash);
    
    // users_count % 2
    int i = 0;
    for(i = 0; i < users_count; i+=2) 
    {
        // pw format is $dahua$hash aka skip 6 chars
        char *hash = &(users[i+1][7]);
        if(strcmp(argv[1], users[i]) == 0 && strcmp(dahua_hash, hash) == 0)
        {
            // authenticated.
            connection_authenticated = 1;
            dyad_writef(stream, "Successful login!\n");
            return;
        }
    }
    
    dyad_writef(stream, "Incorrect username and or password.\n");
}

void cmd_login(dyad_Stream *stream, int argc, char *argv[]) 
{
    if (argc < 2) {
        dyad_writef(stream, "Invalid Arguments (see -h)\n");
        return;
    }
    
    if (strcmp(argv[1], "-h") == 0)
    {
        return login_help(stream);
    }
    
    if (connection_authenticated) 
    {
        dyad_writef(stream, "You're already logged in!\n");
        return;
    }
    
    return login_auth(stream, argc, argv);
}
