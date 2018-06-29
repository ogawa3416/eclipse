package pass18_03_1;
public class Compute {
	public	void run(Mean mean){
		double answer = mean.process();		// 平均を計算して答えを返す
		mean.display(answer);				// 答えを表示する
	}
}

