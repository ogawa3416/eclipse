package pass17_01;
public class Stat extends BasicStat {
    public Stat(double[] data) {    // dataはスーパークラスの初期化に必要
        super(data);                // スーパークラスのコンストラクタ呼び出し
    }
    public double sum() {   // 配列 data の合計を返す
        double total = 0;
        for (double v : getData()) {
            total += v;
        }
        return total;
    }
    public double average() {   // 配列 data の平均を返す
        return sum() / size();
    }
}