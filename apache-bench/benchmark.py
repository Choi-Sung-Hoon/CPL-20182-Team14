import subprocess
import sys


POOLS = [4, 16]


if __name__ == '__main__':
    pool = 4
    servers_count = 0

    if len(sys.argv) == 1:
        with open('servers.txt') as servers_file:
            for i, server in enumerate(servers_file.readlines()):

                for pi, pool in enumerate(POOLS):
                    filename = 'test-{:0>2}-{}.tsv'.format(pool, chr(ord('a') + i))
                    cmdline = './run-ab.sh {} {} {}'.format(
                        server.rstrip(), pool, filename)
                    print(cmdline)
                    subprocess.call(['bash', '-c', cmdline],
                                    stdout=sys.stdout, stderr=sys.stderr)
                    servers_count += 1
    else:
        servers_count = int(sys.argv[1])

    for pi, pool in enumerate(POOLS):
        files_cmd = ' '.join(['test-{:0>2}-{}.tsv'
                              ''.format(pool, chr(ord('a') + i)) for i in range(servers_count)])
        cmdline = ('python3 ../visuals/hhh.py test-{0:0>2}.svg "Test with {0} pools" {1}'
                   ''.format(pool, files_cmd))
        print(cmdline)
        subprocess.call(['bash', '-c', cmdline],
                        stdout=sys.stdout, stderr=sys.stderr)
