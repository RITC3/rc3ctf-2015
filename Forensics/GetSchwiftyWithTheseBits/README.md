GetSchwiftyWithTheseBits - 500 Points
=====================================
This challenge had a zip file but with a truecrypt container at the end of it. The truecrypt container began 21 bytes after the zip ended. So to get the container, you could run this command:

tail -c + $((189 + 21)) <filename> > tc.container

This gives you a truecrypt container now. Also, the zip can be "recovered" by treating the zip as the first part of the file and extracting it. In it should be an image called key, which is the key file used to open the tc container. Then bam you're done.

Flag: **RC3-4960380-KJNGKNK**
