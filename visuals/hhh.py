import csv
import pygal
import sys


def read_tsv(filename: str) -> list:
    count_per_hop = {}
    with open(filename) as tsv:
        for row in csv.DictReader(tsv, delimiter="\t"):
            ttime = row['ttime'][:-1] + '0'
            orig_count = count_per_hop.get(ttime, 0)
            count_per_hop[ttime] = orig_count + 1
    hop_counts = []
    for key, val in count_per_hop.items():
        hop_counts.append((int(key), val))
    return hop_counts


if __name__ == '__main__':
    line_chart =  pygal.Line()
    line_chart = pygal.XY()
    line_chart.x_title = 'Response Time'
    line_chart.y_title = 'Request Count'

    for i, tsv_file in enumerate(sys.argv[1:]):
        hop_counts = read_tsv(tsv_file)
        line_chart.add('Test ' + str(i + 1), hop_counts,stroke_style={'width':4},dots_size=0)
    
    line_chart.render_to_file('test2.svg')