using System;

namespace sample2_2
{
    class Program
    {
        static void Main(string[] args)
        {
            for (int feet = 1; feet <= 10; feet++)
            {
                //フィートからメートルへの対応表を出力
                double meter = FeetToMeter(feet);
                Console.WriteLine("{0} f = {1:0.0000} m", feet, meter);
            }
        }

        //フィートからメートルを求める
        static double FeetToMeter(int feet)
        {
            return feet * 0.3048;
        }
    }
}
