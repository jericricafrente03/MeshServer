#!/bin/bash

ipAddress=$1
package=$2
serverInterface=$3
serverIp=$4
dbHost=$5
dbUser=$6
dbPassword='BittelAsia$2025' #$7
dbName=$8
port=5555
version=''

# Define the allowed models
# allowed_models=("4K Android TV Box" "Pixel 3")
IFS=',' read -ra allowed_models <<< "$9"

##DB AUTHENTICATION
sql="mysql -D'$dbName' -u'$dbUser' -p'$dbPassword' -se "
# run=$($sql "SELECT country_name FROM countries where country_code='PH' limit 1")
# echo "$run"

#arpMac=$(arp-scan --interface=$serverInterface $ipAddress | awk '/.*:.*:.*:.*:.*:.*/{print $2}'  | sed s/://g) 
#Get Mac Address, no root required
mac=$(ip neigh show $ipAddress | awk '{print $5}' | sed s/://g)

#Filter Server's IP
if [ "$ipAddress" != "$serverIp" ]
then
    #Return IP if it is debuggable
    checkPort=$(nmap --open -p T:$port $ipAddress -Pn -oG - | awk '/Up$/{print $2}')

    # Check if the IP exists in adb_devices
    check_ip_exists="$sql \"SELECT COUNT(*) FROM adb_devices WHERE mac_address='$mac' AND ip4_address='$ipAddress';\""
    ip_count=$(eval $check_ip_exists)

    # Check if MAC address exists but with a different IP
    check_mac_exists="$sql \"SELECT COUNT(*) FROM adb_devices WHERE mac_address='$mac';\""
    mac_count=$(eval $check_mac_exists)
    model=$(adb -s $ipAddress:$port shell getprop ro.product.model)
    echo "$model"

    if echo "$model" | grep -q "unauthorized"; then
        check_query="$sql \"SELECT COUNT(*) FROM adb_devices WHERE ip4_address='$ipAddress' AND mac_address='$mac';\""
        count=$(eval $check_query | tail -n 1) # Extract the last line (result count)

        if [[ "$count" -eq 0 ]]; then
            # Insert new entry if neither the IP nor MAC exists
            insert_unauth_query="$sql \"INSERT INTO adb_devices (ip4_address, mac_address, model, created_at, updated_at) VALUES ('$ipAddress', '$mac', 'undebuggable', NOW(), NOW());\""
            eval $insert_unauth_query
            echo "Inserted new unauthorized device: MAC $mac, IP $ipAddress"
        else
            echo "Device already exists in the database. Skipping insert."
        fi
    fi

    # Check if the model is in the allowed list
    model_allowed=false
    for allowed_model in "${allowed_models[@]}"; do
        if [[ "$model" == "$allowed_model" ]]; then
            model_allowed=true
            break
        fi
    done
    
    if [[ "$model_allowed" == true ]]; then
        if [[ "$ip_count" -eq 0 ]]; then
            if [[ "$mac_count" -gt 0 ]]; then
                # If MAC exists but IP is different, update the IP
                update_query="$sql \"UPDATE adb_devices SET ip4_address='$ipAddress', updated_at=NOW() WHERE mac_address='$mac';\""
                eval $update_query
                echo "Updated IP for MAC $mac to $ipAddress"
            else
                # Insert new entry if neither the IP nor MAC exists
                insert_query="$sql \"INSERT INTO adb_devices (ip4_address, mac_address, model, created_at, updated_at) VALUES ('$ipAddress', '$mac', '$model', NOW(), NOW());\""
                eval $insert_query
                echo "Inserted new device: MAC $mac, IP $ipAddress"
            fi
        fi
    else
        echo "Device model $model is not allowed. Skipping database operations."
    fi

    if [[ ! -z "$checkPort" ]]
    then
        connectAdb=$(adb connect $ipAddress)
        currentActivity=$(adb -s $ipAddress:$port shell dumpsys window | grep "mCurrentFocus" | cut -d'/' -f1 | awk -F' ' '{print $NF}') 
        arch=$(adb -s $ipAddress:$port shell getprop ro.product.cpu.abi)
        os_version=$(adb -s $ipAddress:$port shell getprop ro.build.version.release | head -c 1)

        #Get list of package with same name
        packageName=$(adb -s $ipAddress:$port  shell pm list packages $package | awk -F':' '{print $2}')
        #Check if there's a result
        if [ ! -n "$packageName" ]
        then 
            #Update adb_devices with same ip then put in launcher = not yet installed
            sql="$sql \"UPDATE adb_devices SET launcher='not yet installed', launcher_version=null, architecture='$arch', model='$model', foreground='$currentActivity', status='active', updated_at=NOW() WHERE mac_address='$mac' AND model != 'undebuggable';\""
            eval $sql
            echo "not yet installed"
        else
            #When package is installed, we can check the version
            version=$(adb -s $ipAddress:$port shell dumpsys package $package | awk -F" " '/versionName/ {print $1}' | awk -F"=" '{print $2}'| awk '!x[$0]++')
            sql="$sql \"UPDATE adb_devices SET launcher='$packageName', architecture='$arch', model='$model', foreground='$currentActivity', launcher='$packageName', launcher_version='$os_version', status='active', updated_at=NOW() WHERE mac_address='$mac';\""
            eval $sql
            echo "Success"
        fi

        # echo "Device $ipAddress - version: $version, current_activity: $currentActivity, model: $model, architecture: $arch, os_version: $os_version,."
    else
        #this where we update adb_devices with same ip as status = undebuggable
        echo "Undebuggable"
    fi
else
    echo "Server IP: $serverIp"
fi
