FROM ubuntu:latest
LABEL authors="abdiel"

ENTRYPOINT ["top", "-b"]
