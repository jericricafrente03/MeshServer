#!/bin/bash


serverInterface="enp2s0"
meshtvPort="8084"

ipaddress=$1
# result=$(adb connect 192.168.124.23)

# result_clear_cache=$(adb -s 192.168.124.23:5555 shell pm clear com.google.android.apps.mediashell)
#netflix_clear_cache=$(adb -s 192.168.124.23:5555 shell pm clear com.netflix.ninja)
result=$(adb connect $1)

#result_clear_cache=$(adb -s $1:5555 shell pm clear com.google.android.apps.mediashell)
netflix_clear_cache=$(adb -s $1:5555 shell pm clear com.netflix.ninja)
spotify_clear_cache=$(adb -s $1:5555 shell pm clear com.spotify.tv.android)
amazon_clear_cache=$(adb -s $1:5555 shell pm clear com.amazon.amazonvideo.livingroom)
youtube_clear_cache=$(adb -s $1:5555 shell pm clear com.google.android.youtube.tv)
disneyplus_clear_cache=$(adb -s $1:5555 shell pm clear com.disneyplus.ph)
# com.netflix.ninja

# for debugging
    touch /var/www/meshtv/storage/logs/clearcache_debug.txt
    echo "$result" >> /var/www/meshtv/storage/logs/clearcache_debug.txt
    echo "$netflix_clear_cache" >> /var/www/meshtv/storage/logs/clearcache_debug.txt
    echo "$ipaddress" >> /var/www/meshtv/storage/logs/clearcache_debug.txt

    echo "$ipaddress"
    echo "$result"
    # echo "$result_clear_cache"
    echo "$netflix_clear_cache"
    echo "$spotify_clear_cache"
    echo "$amazon_clear_cache"
    echo "$youtube_clear_cache"
    echo "$disneyplus_clear_cache"
