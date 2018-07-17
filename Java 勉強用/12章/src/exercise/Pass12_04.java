package exercise;

import lib.Input;

public class Pass12_04 {

	public static void main(String[] args) {
		double height = Input.getDouble("縦の長さ");
		double width = Input.getDouble("横の長さ");
		double depth = Input.getDouble("縦の長さ");
		if(isOk(height, width, depth)){
            double weight = Input.getDouble("重さ");
            int gaku = ryokin(height, width, depth, weight);
            System.out.println("料金＝" + gaku);
        }else{
            System.out.println("サイズオーバー");
        }
	}
	public static boolean isOk(double height, double width, double depth){
        return  180 >= height + width + depth;
	}
	public  static int ryokin(double height, double width, double depth, double weight){
        double length = height+width+depth;
        int gaku;
        if(length<=90){
            if(weight<=5){
                gaku = 500;
            }else if(weight<=10){
                gaku = 1000;
            }else{
                gaku = 1500;
            }
        }else{
            if(weight<=5){
                gaku = 1000;
            }else if(weight<=10){
                gaku = 2000;
            }else{
                gaku = 3000;
            }           
        }
        return gaku;
    }
}
