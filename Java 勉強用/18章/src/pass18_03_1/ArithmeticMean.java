package pass18_03_1;

import lib.Input;

public class ArithmeticMean extends Mean{
    private double  sum;       
    private int     times;   
     

    @Override
    public  double  process(){
        sum = 0;
        double dt;
        while((dt=Input.getDouble())!=0){   
            sum += dt;                      
            times++;                        
        }
        return  sum/times;                 
    }
     

    @Override
    public  void    display(double answer){
        System.out.println("平均="+answer);
    }
}