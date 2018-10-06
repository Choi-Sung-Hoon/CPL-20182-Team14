import subprocess
import sys


if __name__ == '__main__':
    count = 0
    pool = 4
    tsv_files = []

    with open('servers.txt') as servers_file:
        for server in servers_file.readlines():
            filename = 'test-{}.tsv'.format(count + 1)
            tsv_files.append(filename)
            cmdline = './run-ab.sh {} {} {}'.format(server.rstrip(), pool ** (count + 1), filename)
            print(cmdline)
            subprocess.call(['bash', '-c', cmdline], stdout=sys.stdout, stderr=sys.stderr)
            count += 1

    files_cmd = ' '.join(tsv_files)
    cmdline = 'python3 ../visuals/hhh.py {}'.format(files_cmd)
    print(cmdline)
    subprocess.call(['bash', '-c', cmdline], stdout=sys.stdout, stderr=sys.stderr)
