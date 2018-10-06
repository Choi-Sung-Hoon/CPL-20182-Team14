import subprocess
import sys


POOLS = [4, 16]


if __name__ == '__main__':
    pool = 4
    tsv_files = [[] for _ in POOLS]

    with open('servers.txt') as servers_file:
        for i, server in enumerate(servers_file.readlines()):

            for pi, pool in enumerate(POOLS):
                filename = 'test-{:0>2}-{}.tsv'.format(pool, chr(ord('a') + i))
                tsv_files[pi].append(filename)
                cmdline = './run-ab.sh {} {} {}'.format(server.rstrip(), pool, filename)
                print(cmdline)
                subprocess.call(['bash', '-c', cmdline], stdout=sys.stdout, stderr=sys.stderr)

    for pi, pool in enumerate(POOLS):
        pool_tsvs = tsv_files[pi]
        files_cmd = ' '.join(pool_tsvs)
        cmdline = ('python3 ../visuals/hhh.py test-{0:0>2}.svg "Test with {0:0>2} pools" {1}'
                   ''.format(pool, files_cmd))
        print(cmdline)
        subprocess.call(['bash', '-c', cmdline], stdout=sys.stdout, stderr=sys.stderr)
