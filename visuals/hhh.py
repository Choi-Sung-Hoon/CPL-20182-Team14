import csv
import pygal
import sys


HOP_LIMIT = 4999
HOP_STEP = 20

def read_tsv(filename: str) -> list:
    counts_per_hop = [0 for _ in range((HOP_LIMIT + 1) // HOP_STEP)]
    with open(filename) as tsv:
        for row in csv.DictReader(tsv, delimiter="\t"):
            ttime_index = int(row['ttime']) // HOP_STEP
            counts_per_hop[ttime_index] += 1
    return counts_per_hop


if __name__ == '__main__':
    print('The first argument is the output file name, the second argument is its title, '
          'and followings are TSV files.')

    custom_style = pygal.style.Style()

    line_chart = pygal.Line(
        x_label_rotation=1,
        x_labels_major_every=HOP_LIMIT // (HOP_STEP * 10),
        show_minor_x_labels=False,
        style=custom_style
    )
    line_chart.title = sys.argv[2]
    line_chart.x_title = 'Connection Time'
    line_chart.y_title = 'Request Count'
    line_chart.x_labels = [ttime for ttime in range(0, HOP_LIMIT, HOP_STEP)]

    for i, tsv_file in enumerate(sys.argv[3:]):
        hop_counts = read_tsv(tsv_file)
        line_chart.add('Cluster ' + chr(ord('A') + i), hop_counts, stroke_style={'width':4},dots_size=0)
    
    line_chart.render_to_file(sys.argv[1])
