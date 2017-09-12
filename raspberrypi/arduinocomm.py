#!/usr/bin/env python3
import serial
import sys

arg = str(sys.argv[1])

port = serial.Serial("/dev/ttyAMAO", baudrate=9600, timeout=3.0)

port.open()
content = b"\0x01"

port.write(content)

port.close
	
