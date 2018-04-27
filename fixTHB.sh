#!/usr/bin/env bash

# To remove this warning:

# redis_1    | 1:M 17 May 15:28:15.295
# WARNING you have Transparent Huge Pages (THP) support enabled in your kernel.
# This will create latency and memory usage issues with Redis.
# To fix this issue run the command
# 'echo never > /sys/kernel/mm/transparent_hugepage/enabled' as root,
# and add it to your /etc/rc.local in order to retain the setting after a reboot.
# Redis must be restarted after THP is disabled.

# please use this:

docker run --rm --privileged -ti alpine /bin/sh -c \
    "echo never > /sys/kernel/mm/transparent_hugepage/enabled && echo never > /sys/kernel/mm/transparent_hugepage/defrag"

# http://larstechnica.com/2017/06/docker-for-mac-fix-for-max-files-transparent-hugepages-warnings

# Transparent Huge Pages (THP) is a Linux memory management system that reduces the overhead of
# Translation Lookaside Buffer (TLB) lookups on machines with large amounts of memory by using larger memory pages.

# However, database workloads often perform poorly with THP,
# because they tend to have sparse rather than contiguous memory access patterns.
# You should disable THP on Linux machines to ensure best performance.

# https://docs.mongodb.com/manual/tutorial/transparent-huge-pages/