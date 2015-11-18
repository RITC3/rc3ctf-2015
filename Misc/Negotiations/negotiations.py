from OpenSSL import SSL
import socket
from os import fork
from sys import exit

ctx = SSL.Context(SSL.SSLv3_METHOD)
ctx.use_privatekey_file('server.key')
ctx.use_certificate_file('server.crt')

s = SSL.Connection(ctx, socket.socket(socket.AF_INET, socket.SOCK_STREAM))
s.bind(('0.0.0.0', 1072))
s.listen(20)

while 1:
    c, addr = s.accept()
    print("Connection from "+addr[0])
    if not fork():
        s.close()
        try: c.do_handshake()
        except Exception as e: print("Err:" + str(e))
        c.close()
        exit(0)
    c.close()
