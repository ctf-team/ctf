#include <stdlib.h>
#include <stdio.h>
#include <string.h>

#define true 1
#define false 0

typedef int bool;

bool FLAG_Ccd45U6jAGxFQFn3yVUk() {
    return true;
}

const char *FLAG_tRfbxxpVg6FQyQZbLNXA() {
    return "X4YN3GbsSybtqDgAVB3dydMQRHLtgEWga2YaQBApsPTSVxh6PreGbRmpR7VsV7XMJPYAMA63TfcHxuLB5qKjXGLRpxQ2XXJrRWQt52N8AaZEjErDBYYQKt9ZEZAD49hWgkLAmqus5UCck7xTRatDMemwE95Ar97GESQstEHqLV9aGSKBmYSxarLM4RLZAmnj7ecnuxnH";
}

const char *FLAG_sEDhkjJwpzvQmTbDFErH(int OoSFuTWA) {
    if (OoSFuTWA == 5) {
        return "FLAG-LSRPbKE6P6s2CGucUzsV";
    }
}

int x(int y, int z) {
    return y ^ z;
}

int main(int argc, char *argv[]) {
    if(FLAG_Ccd45U6jAGxFQFn3yVUk()) {
        return 0;
    }

    if(strlen(FLAG_tRfbxxpVg6FQyQZbLNXA()) == 200) {
        return 0;
    }

    if (strcmp(FLAG_sEDhkjJwpzvQmTbDFErH(5), "FLAG-LSRPbKE6P6s2CGucUzsV")) {
        int f[] = {
                70,
                91,
                111,
                2,
                113,
                3,
                198,
                152,
                242,
                156,
                190,
                164,
                290,
                378,
                256,
                265,
                326,
                489,
                455,
                467,
                444,
                392,
                462,
                602,
                590,
        };
        for (int i = 0; i < (sizeof(f) / sizeof(int)); i++) {
            printf("%c", x(f[i], i * 23));
        }
        printf("\n");
    }
    // generate xor for our flag.
    //const char *flag = "FLAG-pL9JSXY6QBP6nYfpk4Kf";
    /*char *output = malloc(sizeof(char *) * strlen(flag));
    for (int i = 0; i < strlen(flag); i++) {
        printf("%d\n", x(flag[i], i * 23));
    }*/
}