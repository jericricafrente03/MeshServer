#!/bin/bash
LOGFILE=$4

LOCKFILE="/tmp/adb_analytics_ping_device.lock"

# Record start time
start_time=$(date +%s)

# Check if another instance is running
if [ -e "$LOCKFILE" ]; then
    echo "[$(date)] Script is already running. Exiting..." >> "$LOGFILE"
    exit 1
fi

# Create the lock file
touch "$LOCKFILE"

# Ensure the lock file is removed when the script exits or errors
trap 'rm -f "$LOCKFILE"; exit 1' EXIT ERR

# Check if the log file exists, if not create it and set permissions
if [ ! -f "$LOGFILE" ]; then
    touch "$LOGFILE"
    chmod 666 "$LOGFILE"
fi

# Check if the log file has more than 500 lines
if [ $(wc -l < "$LOGFILE") -gt 500 ]; then
    echo "[$(date)] Log file exceeded 500 lines. Clearing it..." > "$LOGFILE"
fi

# Redirect all output (stdout and stderr) to the log file
exec >> "$LOGFILE" 2>&1

# Echo datatime
echo "[$(date)] Ping Device Script started."

php /var/www/html/artisan devices:updated failed
php /var/www/html/artisan devices:updated system_info

serverInterface=$1
package=$2
serverIp=$3
dbHost=$5
dbUser=$6
dbPassword=$7
dbName=$8
port='5555'

#DB AUTHENTICATION
sql="mysql -D'$dbName' -u'$dbUser' -p'$dbPassword' -se "

# Get ServerIP base on the serverInterface
serverIP=$(ifconfig $serverInterface | grep "inet " | awk '{print $2}')
# Get ServerSubnet
subnet=$(ip -o -f inet addr show | awk '/scope global/ {print $4}' | grep $serverIP)

# Get active IPs in the subnet
mapfile -t active < <(nmap -sP $subnet | awk '/is up/ {print up}; {gsub (/\(|\)/,""); up = $NF}')

update_query="$sql \"UPDATE devices SET current_status='Inactive', updated_at=NOW() WHERE id != '0';\""
eval $update_query

# Loop through each active IP and call another script
for IP in "${active[@]}"
    do  
        #Get list of package with same name
        packageName=$(adb -s $IP:$port  shell pm list packages $package | awk -F':' '{print $2}')
        #Check if there's a result
        if [ ! -n "$packageName" ]
        then 
            echo "$IP - $mac not yet installed"
        else
            mac=$(ip neigh show $IP | awk '{print $5}' | sed s/://g)
            # Only continue if there is a MAC address
            if [[ -n "$mac" ]]; then
                update_query="$sql \"UPDATE devices SET current_status='Active', updated_at=NOW() WHERE ip4_address='$IP' AND mac_address='$mac';\""
                eval $update_query
                echo "Updated status with $IP - $mac"
            else
                echo "Error: No MAC address found for IP $IP"
            fi
        fi
done   
  
php /var/www/html/artisan devices:updated stb

# Calculate execution time
end_time=$(date +%s)
elapsed_time=$((end_time - start_time))

# Log script completion with execution time
echo "[$(date)] Ping Device Script completed in $elapsed_time seconds."
