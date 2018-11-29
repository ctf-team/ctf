import os
import string
import random
import time

current_tokens = {}


def check_expired_token(token):
    if token in current_tokens.keys():
        # check that it's not expired.
        if time.time() <= current_tokens[token]:
           return True
        current_tokens.pop(token)
        return False
    return False


def cmd_help(args):
    helpstr = "List of commands:\n"
    for key, val in commands.items():
        if key == "exec":
            continue
        helpstr += "\t- "+key+" "+val[0]+"\n"
    return helpstr


def cmd_token(args):
    key = ''.join(random.choice(string.ascii_uppercase + string.digits) for _ in range(32))
    current_tokens[key] = time.time() + 3
    return ("Your temporary token: \'" + key + "\'\n"
            "Note, this token expires in 3 seconds.")


def cmd_secret(args):
    if check_expired_token(args[1]):
        return "Here's your secret: \'" + os.getenv("flag") + "\'"
    return "Invalid token!"


def cmd_exec(args):
    if len(args) == 1:
        return "Command not found. Please use 'help'."
    if check_expired_token(args[1]):
        os.system(args[0])
    return ""


commands = {
    "help": ["", cmd_help],
    "token": ["", cmd_token],
    "secret": ["<token>", cmd_secret],
    "exec": ["<token>", cmd_exec]
}


def switcher(args):
    if len(args) == 0:
        return "bash: command not found"
    else:
        func = commands.get(args[0], commands["exec"])
        return func[1](args)
