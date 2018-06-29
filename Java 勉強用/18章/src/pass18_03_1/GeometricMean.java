package pass18_03_1;

import lib.Input;

public class GeometricMean extends Mean {
	private	double	sum;
	private	int		times;
	@Override
	public	double	process(){
		sum = 1;
		double dt;
		while((dt=Input.getDouble())!=0){
			sum *= dt;
			times++;
		}
		return	Math.pow(sum, 1.0/times);
	}
	@Override
	public	void	display(double mean){
		System.out.println("平均="+ mean);
	}
}
