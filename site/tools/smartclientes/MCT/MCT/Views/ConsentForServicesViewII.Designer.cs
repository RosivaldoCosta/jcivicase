
//----------------------------------------------------------------------------------------
// patterns & practices - Smart Client Software Factory - Guidance Package
//
// This file was generated by the "Add View" recipe.
//
// For more information see: 
// ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/02-09-010-ModelViewPresenter_MVP.htm
//
// Latest version of this Guidance Package: http://go.microsoft.com/fwlink/?LinkId=62182
//----------------------------------------------------------------------------------------

using Sante.EMR.SmartClient.Module.Constants;
namespace Sante.EMR.SmartClient.MCT
{
    partial class ConsentForServicesViewII
    {
        /// <summary>
        /// The presenter used by this view.
        /// </summary>
        private Sante.EMR.SmartClient.MCT.ConsentForServicesViewPresenter _presenter = null;

        /// <summary> 
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary> 
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            

            base.Dispose(disposing);
        }

        #region Component Designer generated code

        /// <summary> 
        /// Required method for Designer support - do not modify 
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(ConsentForServicesViewII));
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this.groupBox3 = new System.Windows.Forms.GroupBox();
            this.sigpad = new MyProject.MyPanel();
            this.groupBox2 = new System.Windows.Forms.GroupBox();
            this.label6 = new System.Windows.Forms.Label();
            this.label5 = new System.Windows.Forms.Label();
            this.label4 = new System.Windows.Forms.Label();
            this.Why = new System.Windows.Forms.TextBox();
            this.UnableSign = new System.Windows.Forms.CheckBox();
            this.ParentSignature = new System.Windows.Forms.TextBox();
            this.Parent = new System.Windows.Forms.TextBox();
            this.label3 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.label1 = new System.Windows.Forms.Label();
            this.TimeStamp = new System.Windows.Forms.DateTimePicker();
            this.CaseNumber = new System.Windows.Forms.ComboBox();
            this.toolStrip1 = new System.Windows.Forms.ToolStrip();
            this.toolStripButton1 = new System.Windows.Forms.ToolStripButton();
            this.Depart = new System.Windows.Forms.ComboBox();
            this.label7 = new System.Windows.Forms.Label();
            this.groupBox1.SuspendLayout();
            this.groupBox3.SuspendLayout();
            this.groupBox2.SuspendLayout();
            this.toolStrip1.SuspendLayout();
            this.SuspendLayout();
            // 
            // groupBox1
            // 
            this.groupBox1.Controls.Add(this.groupBox3);
            this.groupBox1.Controls.Add(this.groupBox2);
            this.groupBox1.Controls.Add(this.toolStrip1);
            this.groupBox1.Dock = System.Windows.Forms.DockStyle.Fill;
            this.groupBox1.Location = new System.Drawing.Point(0, 0);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Size = new System.Drawing.Size(501, 600);
            this.groupBox1.TabIndex = 3;
            this.groupBox1.TabStop = false;
            this.groupBox1.Text = "Consent For Services";
            // 
            // groupBox3
            // 
            this.groupBox3.Controls.Add(this.sigpad);
            this.groupBox3.Location = new System.Drawing.Point(6, 385);
            this.groupBox3.Name = "groupBox3";
            this.groupBox3.Size = new System.Drawing.Size(486, 183);
            this.groupBox3.TabIndex = 4;
            this.groupBox3.TabStop = false;
            this.groupBox3.Text = "Signature Pad";
            // 
            // sigpad
            // 
            this.sigpad.BackColor = System.Drawing.SystemColors.ButtonHighlight;
            this.sigpad.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.sigpad.Location = new System.Drawing.Point(6, 19);
            this.sigpad.Name = "sigpad";
            this.sigpad.Size = new System.Drawing.Size(474, 157);
            this.sigpad.TabIndex = 2;
            this.sigpad.Initialize();
            // 
            // groupBox2
            // 
            this.groupBox2.Controls.Add(this.label7);
            this.groupBox2.Controls.Add(this.Depart);
            this.groupBox2.Controls.Add(this.label6);
            this.groupBox2.Controls.Add(this.label5);
            this.groupBox2.Controls.Add(this.label4);
            this.groupBox2.Controls.Add(this.Why);
            this.groupBox2.Controls.Add(this.UnableSign);
            this.groupBox2.Controls.Add(this.ParentSignature);
            this.groupBox2.Controls.Add(this.Parent);
            this.groupBox2.Controls.Add(this.label3);
            this.groupBox2.Controls.Add(this.label2);
            this.groupBox2.Controls.Add(this.label1);
            this.groupBox2.Controls.Add(this.TimeStamp);
            this.groupBox2.Controls.Add(this.CaseNumber);
            this.groupBox2.Location = new System.Drawing.Point(6, 44);
            this.groupBox2.Name = "groupBox2";
            this.groupBox2.Size = new System.Drawing.Size(486, 335);
            this.groupBox2.TabIndex = 3;
            this.groupBox2.TabStop = false;
            this.groupBox2.Text = "Consent For Services";
            // 
            // label6
            // 
            this.label6.AutoSize = true;
            this.label6.Location = new System.Drawing.Point(6, 46);
            this.label6.MaximumSize = new System.Drawing.Size(480, 0);
            this.label6.Name = "label6";
            this.label6.Size = new System.Drawing.Size(33, 13);
            this.label6.TabIndex = 11;
            this.label6.Text = "Date:";
            // 
            // label5
            // 
            this.label5.AutoSize = true;
            this.label5.Location = new System.Drawing.Point(6, 19);
            this.label5.MaximumSize = new System.Drawing.Size(480, 0);
            this.label5.Name = "label5";
            this.label5.Size = new System.Drawing.Size(74, 13);
            this.label5.TabIndex = 10;
            this.label5.Text = "Case Number:";
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(6, 243);
            this.label4.MaximumSize = new System.Drawing.Size(480, 0);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(35, 13);
            this.label4.TabIndex = 9;
            this.label4.Text = "Why?";
            // 
            // Why
            // 
            this.Why.Location = new System.Drawing.Point(9, 268);
            this.Why.Multiline = true;
            this.Why.Name = "Why";
            this.Why.Size = new System.Drawing.Size(469, 57);
            this.Why.TabIndex = 8;
            // 
            // UnableSign
            // 
            this.UnableSign.AutoSize = true;
            this.UnableSign.CheckAlign = System.Drawing.ContentAlignment.MiddleRight;
            this.UnableSign.Location = new System.Drawing.Point(9, 212);
            this.UnableSign.Name = "UnableSign";
            this.UnableSign.Size = new System.Drawing.Size(94, 17);
            this.UnableSign.TabIndex = 7;
            this.UnableSign.Text = "Unable to sign";
            this.UnableSign.TextAlign = System.Drawing.ContentAlignment.MiddleRight;
            this.UnableSign.UseVisualStyleBackColor = true;
            // 
            // ParentSignature
            // 
            this.ParentSignature.Location = new System.Drawing.Point(183, 174);
            this.ParentSignature.Name = "ParentSignature";
            this.ParentSignature.Size = new System.Drawing.Size(100, 20);
            this.ParentSignature.TabIndex = 6;
            // 
            // Parent
            // 
            this.Parent.Location = new System.Drawing.Point(23, 160);
            this.Parent.Name = "Parent";
            this.Parent.Size = new System.Drawing.Size(100, 20);
            this.Parent.TabIndex = 5;
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(123, 158);
            this.label3.MaximumSize = new System.Drawing.Size(370, 0);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(365, 26);
            this.label3.TabIndex = 4;
            this.label3.Text = "(parent/guardian) give Affiliated Sante Group permission to provide services to m" +
                "y child,";
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(6, 163);
            this.label2.MaximumSize = new System.Drawing.Size(480, 0);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(13, 13);
            this.label2.TabIndex = 3;
            this.label2.Text = "I,";
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(4, 83);
            this.label1.MaximumSize = new System.Drawing.Size(480, 0);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(474, 65);
            this.label1.TabIndex = 2;
            this.label1.Text = resources.GetString("label1.Text");
            // 
            // TimeStamp
            // 
            this.TimeStamp.Format = System.Windows.Forms.DateTimePickerFormat.Short;
            this.TimeStamp.Location = new System.Drawing.Point(86, 46);
            this.TimeStamp.Name = "TimeStamp";
            this.TimeStamp.Size = new System.Drawing.Size(121, 20);
            this.TimeStamp.TabIndex = 1;
            // 
            // CaseNumber
            // 
            this.CaseNumber.FormattingEnabled = true;
            this.CaseNumber.Location = new System.Drawing.Point(87, 19);
            this.CaseNumber.Name = "CaseNumber";
            this.CaseNumber.Size = new System.Drawing.Size(121, 21);
            this.CaseNumber.TabIndex = 0;
            // 
            // toolStrip1
            // 
            this.toolStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.toolStripButton1});
            this.toolStrip1.Location = new System.Drawing.Point(3, 16);
            this.toolStrip1.Name = "toolStrip1";
            this.toolStrip1.Size = new System.Drawing.Size(495, 25);
            this.toolStrip1.TabIndex = 1;
            this.toolStrip1.Text = "toolStrip1";
            // 
            // toolStripButton1
            // 
            this.toolStripButton1.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Text;
            this.toolStripButton1.Image = ((System.Drawing.Image)(resources.GetObject("toolStripButton1.Image")));
            this.toolStripButton1.ImageTransparentColor = System.Drawing.Color.Magenta;
            this.toolStripButton1.Name = "toolStripButton1";
            this.toolStripButton1.Size = new System.Drawing.Size(35, 22);
            this.toolStripButton1.Text = "Save";
            this.toolStripButton1.Click += new System.EventHandler(this.toolStripButton1_Click);
            // 
            // Depart
            // 
            this.Depart.FormattingEnabled = true;
            this.Depart.Items.AddRange(new object[] {
            "MCT1",
            "MCT2"});
            this.Depart.Location = new System.Drawing.Point(330, 19);
            this.Depart.Name = "Depart";
            this.Depart.Size = new System.Drawing.Size(60, 21);
            this.Depart.TabIndex = 12;
            this.Depart.Text = "MCT1";
            // 
            // label7
            // 
            this.label7.AutoSize = true;
            this.label7.Location = new System.Drawing.Point(259, 22);
            this.label7.MaximumSize = new System.Drawing.Size(480, 0);
            this.label7.Name = "label7";
            this.label7.Size = new System.Drawing.Size(65, 13);
            this.label7.TabIndex = 13;
            this.label7.Text = "Department:";
            // 
            // ConsentForServicesViewII
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.Controls.Add(this.groupBox1);
            this.Name = "ConsentForServicesViewII";
            this.Size = new System.Drawing.Size(501, 600);
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            this.groupBox3.ResumeLayout(false);
            this.groupBox2.ResumeLayout(false);
            this.groupBox2.PerformLayout();
            this.toolStrip1.ResumeLayout(false);
            this.toolStrip1.PerformLayout();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.ToolStrip toolStrip1;
        private System.Windows.Forms.ToolStripButton toolStripButton1;
        private MyProject.MyPanel sigpad;
        private System.Windows.Forms.GroupBox groupBox2;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.DateTimePicker TimeStamp;
        private System.Windows.Forms.ComboBox CaseNumber;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label5;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.TextBox Why;
        private System.Windows.Forms.CheckBox UnableSign;
        private System.Windows.Forms.TextBox ParentSignature;
        private System.Windows.Forms.TextBox Parent;
        private System.Windows.Forms.Label label6;
        private System.Windows.Forms.GroupBox groupBox3;
        private System.Windows.Forms.Label label7;
        private System.Windows.Forms.ComboBox Depart;
    }
}

