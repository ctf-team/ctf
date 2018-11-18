- file structure
    - auth.bin
    - flag.txt
    - main program
    - recordings
        - my_recording.log

--- unauthenticated commands ---

save authentication password in auth.bin file


- help [command]
    - show brief description of command
- record start
    - save start time to named file
    - record -start <filename>
    - filename will be based in recordings folder
- record stop
    - save stop time to named file
    - record -stop <filename>
    - filename will be based in recordings folder
- record info
    - reads the file and outputs the content
    - record -info <filename>
- list recordings
    - list recordings in folder
    - record -list [directory]
- delete recordings
    - only delete file if it contains some string
        eg so that they dont delete the flag file
    - or only delete if the path resolved
    - record -delete <filename>
- admin


--- authenticated commands ---

- set hostname/name
- get mac address
- get ip address
- list uptime

- debug command
    * prints base address

