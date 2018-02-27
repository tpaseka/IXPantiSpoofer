# IXPantiSpoofer

This is a PoC. lots of manual steps and needs a lot of refinement.

sflowtool (https://github.com/sflow/sflowtool)
bgpq3 (https://github.com/snar/bgpq3)
https://github.com/job/aggregate6
are requirement for this example.

Process:
. Take ARP table and bgp summary from your router (arp.txt, bgp.txt) 
. Download IRR set with bgpq3 and aggregate with aggregate/aggregate6 (to be scripted)
. munge data so that you have files of:
.. each AS-SET (named by AS SET or ASN)
.. irr-mac CSV file, containing mac address and AS-SET name (or file-name where AS-SET contents are)
. aggregate data in AS-SETs for least entries (least specific routes). 
. Receives sflow packets in text format from sflowtool 
. Matches MAC address to IRR set and checks if IP address is member of IRR set.
