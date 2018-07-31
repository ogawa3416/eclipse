package pass17_01;
public class Exec {
    public static void main(String[] args) {
        double[] data ={65.1, 60.3, 75.5, 70.0, 67.3};
        Stat stat = new Stat(data);
        System.out.println("最小値＝"+stat.min()+"￥t最大値＝"+stat.max());
        System.out.println("合　計＝"+stat.sum()+"￥t平　均＝"+stat.average());
    }
}
