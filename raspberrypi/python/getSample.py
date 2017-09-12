#!/usr/bin/env python3
import sqlite3
import threading
import time
import collections
import raspberryPi_to_arduino as arduinoServer

db_conn = sqlite3.connect("/var/www/sensors.db")
c = db_conn.cursor()

moisture_values_windows = {}

arduino_comm = arduinoServer.arduino_comm()

def get_moisture_values():
    c.execute("SELECT pinNumber FROM moistureSensors")
    moistureSensors = c.fetchall()
    #print("Requesting Moisture Values")
    for sensor in moistureSensors:
        latest_value = arduino_comm.serial_message(*arduino_comm.request_moisture(sensor[0]))
        c.execute("INSERT INTO moistureValues VALUES (?, ?, ?, ?)", (time.strftime("%d/%m/%Y"), time.strftime("%H:%M:%S"), sensor[0], latest_value))
        #moisture_values_windows[sensor[0]].append(latest_value)
        #update_averages(sensor[0])
    db_conn.commit()
    
get_moisture_values()
