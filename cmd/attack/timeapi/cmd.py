import os
import string
import random
import time

current_tokens = {}

def cmd_help(args):
    helpstr = "List of commands:\n"
    for key, val in commands.items():
        helpstr += "\t- "+key+" "+val[0]+"\n"
    return helpstr


def cmd_token(args):
    key = ''.join(random.choice(string.ascii_uppercase + string.digits) for _ in range(32))
    current_tokens[key] = time.time() + 3
    return ("Your temporary token: \'" + key + "\'\n"
            "Note, this token expires in 3 seconds.")


def cmd_secret(args):
    if args[1] in current_tokens.keys():
        # check that it's not expired.
        if time.time() <= current_tokens[args[1]]:
            return "Here's your secret: \'" + os.getenv("flag") + "\'"
        else:
            current_tokens.pop(args[1])
            return "Sorry, that token has expired!"

    return "Invalid token!"


commands = {
    "help": ["", cmd_help],
    "token": ["", cmd_token],
    "secret": ["<token>", cmd_secret]
}


def switcher(args):
    print(args[0])
    func = commands.get(args[0], commands["help"])
    return func[1](args)
