#!/usr/bin/env python

key = "RC3-MNTY-1072"

# encode function
c = 0
ct = []

for j, i in enumerate(key):
    ct.append((c & 0b10101010) ^ ord(i))
    ct.append(ct[j*2] & 0x69)
    c += ct[j*2]

#print "".join(chr(a) for a in ct)
print ct
print [ hex(x) for x in ct ]

# decode function
c = 0
pt = []
for i in range(0, len(ct), 2):
    pt.append((c & 0b10101010) ^ ct[i])
    c += ct[i]

print "".join(chr(x) for x in pt)
