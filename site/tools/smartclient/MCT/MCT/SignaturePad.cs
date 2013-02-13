using System;
using System.Collections.Generic;
using System.Text;
using System.Drawing;
using System.Collections;
using System.ComponentModel;
using System.Windows.Forms;
using System.Data;
using System.Drawing.Imaging;

namespace MyProject
{
    public class MyPanel : Panel
    {
        private Pen p,p2;
        public string pen_color = "black";
        private bool mouse_down = false;
        private Point last_point = Point.Empty;
        private Graphics g, g2;
        Bitmap b; // = new Bitmap(this.Width, this.Height, g);
            

        public MyPanel()
        {
            //p.Brush = new Brush();

        }
        protected override void OnMouseDown(MouseEventArgs e)
        {
            mouse_down = true;
        }
        protected override void OnMouseUp(MouseEventArgs e)
        {
            mouse_down = false;
        }
        protected override void OnMouseMove(MouseEventArgs e)
        {
            if (last_point.Equals(Point.Empty)) last_point = new
Point(e.X, e.Y);
            if (mouse_down)
            {
                try
                {
                    Initialize();                
                    Point pMousePos = new Point(e.X, e.Y);
                    g.DrawLine(p, pMousePos, last_point);
                    g2.DrawLine(p2, pMousePos, last_point);
                    //g.DrawImage(b, pMousePos);
                }
                catch (Exception exp)
                {
                    MessageBox.Show(exp.Message);
                }
            }
            last_point = new Point(e.X, e.Y);
        }

        public void Initialize()
        {
             p = new Pen(Color.FromName(pen_color));
             p2 = new Pen(Color.FromName("black"));
             b = new Bitmap(this.Width, this.Height);
             g = Graphics.FromImage(b);
             g.FillRegion(new SolidBrush(Color.White), new Region(new Rectangle(0,0,this.Width,this.Height)));
             g2 = this.CreateGraphics();
        }

        public void Save()
        {
            try
            {
             
                //Rectangle rectangle = new Rectangle(0, 0, this.Width, this.Height);
                //this.DrawToBitmap(b, rectangle);
                //b.Save("this.bmp", ImageFormat.Bmp);

                int width = this.Width;
                int height = this.Height;
                this.DrawToBitmap(b, new Rectangle(0, 0, width, height));
                b.Save("this.bmp", ImageFormat.Bmp);

            }
            catch (Exception exp)
            {
                MessageBox.Show(exp.Message);
            }            
        }
    }

} 