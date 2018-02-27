# IXPantiSpoofer

This is a PoC. lots of manual steps and needs a lot of refinement.

Requirements:
- sflowtool (https://github.com/sflow/sflowtool)
- bgpq3 (https://github.com/snar/bgpq3)
- aggregate6 https://github.com/job/aggregate6
- php

Process:
- Take ARP table and bgp summary from your router (arp.txt, bgp.txt) 
- Download IRR set with bgpq3 and aggregate with aggregate/aggregate6 (to be scripted)
- munge data so that you have files of:
  - each AS-SET (named by AS SET or ASN)
  - mac-irr CSV file, containing mac address and AS-SET name (or file-name where AS-SET contents are)
- aggregate data in AS-SETs for least entries (least specific routes). 
- Receives sflow packets in text format from sflowtool 
- Matches MAC address to IRR set and checks if IP address is member of IRR set.

Application is executed in CLI as 
```
# sflowtool -p 9888 -l | php sflow.php
```
with sflowtool listening on the port you defined and with the "-l" flag, for CSV readable input

Output matching would look like:
```
# sflowtool -p 9888 -l | php sflow.php
defining AS-SETs to MAC matching
loading the IRR data into memory.
collecting flow data.....
Packet didnt match irr: Source: 192.168.1.23, 	Destination:104.16.23.235,	MAC:012345678901, 	IRR SET:AS-XXX
```
