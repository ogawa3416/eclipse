package pass17_01;
import java.util.Arrays;
public class BasicStat{
	private	double[] data;
	public	BasicStat(double[] data){
		this.data	=	data;
		Arrays.sort(data);
	}	
	public	double	min(){
		return	data[0];
	}
	public	double	max(){
		return	data[data.length-1];
	}		
	public	int	size(){
		return	data.length;
	}
	public double[] getData() {
		return data;
	}
}
