package column_19;

import java.util.Arrays;
public class Exec {
	public static void main(String[] args) {
		Student[] stu = {new Student("田中宏", 2035), new Student("佐藤修", 1165), new Student("嶋次郎", 1033)};
		Arrays.sort(stu);
		for(Student st : stu){
			System.out.print(st+"\t");
		}
	}
}
