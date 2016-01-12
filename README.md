# SlackPipe (CLI)
SlackPipe is a php cli program to pipe or upload data to various services.

Supported providers:
* Slack
* Jira

## Slack provider
Slack chat provider usage.

#### slack:post
Command signature
```bash
./slackpipe slack:post [-k|--key [KEY]] [-cfg|--config] [-c|--channel CHANNEL] [-u|--user [USER]]
```
Pipe text to slack channel
```bash
echo "Nothing to say here." | ./slackpipe post
```

#### slack:upload
Command signature
```bash
./slackpipe slack:upload [-k|--key [KEY]] [-cfg|--config] [-c|--channel CHANNEL] [-f|--file [FILE]] [--filename [FILENAME]] [-t|--type [TYPE]] [--title [TITLE]]
```

Upload a file to slack channel
```bash
./slackpipe slack:upload -f snippet.php
```

Pipe data as file attachment to slack channel
```bash
echo "Nothing to say here." | ./slackpipe slack:upload
```
You can even specify a filename
```bash
echo "Nothing to say here." | ./slackpipe slack:upload --filename example.txt
```

## Jira issues provider
Jira provider usage.

#### jira:post

Command signature
```bash
./slackpipe jira:post <issue>
```

Pipe `ls` output to comment
```bash
ls | ./slackpipe jira:post -t SLAC-1
```

#### jira:upload

Command signature
```bash
./slackpipe jira:upload [-f|--file [FILE]] [--filename [FILENAME]] [-t|--type [TYPE]] <issue>
```
Upload file
```bash
./slackpipe jira:upload -f run.php SLAC-1
```

Pipe `ls` output to file as file (default type: `.txt`)
```bash
ls | ./slackpipe jira:upload -t sh SLAC-1
```

Pipe `ls` output to file. (specify file type with option: `-t`)
```bash
ls | ./slackpipe jira:upload -t sh SLAC-1
```

## Build

* Install box https://github.com/box-project/box2
* Then run `box build`

In my situation I used:
```
 php -d phar.readonly=off box.phar build
```