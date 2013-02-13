using System.Windows.Forms;
namespace Sante.EMR.SmartClient.Infrastructure.Interface.WinForms
{
    partial class UserSelectionForm
    {
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
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.UserNameTextEdit = new System.Windows.Forms.TextBox();
            this.PasswordTextEdit = new System.Windows.Forms.TextBox();
            this.UserNamelabel = new System.Windows.Forms.Label();
            this.Passwordlabel = new System.Windows.Forms.Label();
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this._messageLabel = new System.Windows.Forms.Label();
            this.LogoPanel = new System.Windows.Forms.Panel();
            this.LogoPictureBox = new System.Windows.Forms.PictureBox();
            this.button1 = new System.Windows.Forms.Button();
            this.groupBox1.SuspendLayout();
            this.LogoPanel.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.LogoPictureBox)).BeginInit();
            this.SuspendLayout();
            // 
            // UserNameTextEdit
            // 
            this.UserNameTextEdit.Location = new System.Drawing.Point(100, 20);
            this.UserNameTextEdit.Name = "UserNameTextEdit";
            this.UserNameTextEdit.Size = new System.Drawing.Size(201, 20);
            this.UserNameTextEdit.TabIndex = 0;
            // 
            // PasswordTextEdit
            // 
            this.PasswordTextEdit.Location = new System.Drawing.Point(100, 62);
            this.PasswordTextEdit.Name = "PasswordTextEdit";
            this.PasswordTextEdit.PasswordChar = '*';
            this.PasswordTextEdit.Size = new System.Drawing.Size(201, 20);
            this.PasswordTextEdit.TabIndex = 2;
            this.PasswordTextEdit.UseSystemPasswordChar = true;
            // 
            // UserNamelabel
            // 
            this.UserNamelabel.Location = new System.Drawing.Point(16, 27);
            this.UserNamelabel.Name = "UserNamelabel";
            this.UserNamelabel.Size = new System.Drawing.Size(60, 13);
            this.UserNamelabel.TabIndex = 3;
            this.UserNamelabel.Text = "Username:";
            // 
            // Passwordlabel
            // 
            this.Passwordlabel.Location = new System.Drawing.Point(18, 65);
            this.Passwordlabel.Name = "Passwordlabel";
            this.Passwordlabel.Size = new System.Drawing.Size(68, 17);
            this.Passwordlabel.TabIndex = 4;
            this.Passwordlabel.Text = "Password:";
            // 
            // groupBox1
            // 
            this.groupBox1.Controls.Add(this.UserNameTextEdit);
            this.groupBox1.Controls.Add(this.Passwordlabel);
            this.groupBox1.Controls.Add(this.PasswordTextEdit);
            this.groupBox1.Controls.Add(this.UserNamelabel);
            this.groupBox1.Location = new System.Drawing.Point(33, 79);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Size = new System.Drawing.Size(327, 106);
            this.groupBox1.TabIndex = 5;
            this.groupBox1.TabStop = false;
            this.groupBox1.Text = "Login";
            // 
            // _messageLabel
            // 
            this._messageLabel.AutoSize = true;
            this._messageLabel.Location = new System.Drawing.Point(134, 190);
            this._messageLabel.Name = "_messageLabel";
            this._messageLabel.Size = new System.Drawing.Size(0, 13);
            this._messageLabel.TabIndex = 6;
            // 
            // LogoPanel
            // 
            this.LogoPanel.BackColor = System.Drawing.Color.White;
            this.LogoPanel.Controls.Add(this.LogoPictureBox);
            this.LogoPanel.Location = new System.Drawing.Point(-1, 0);
            this.LogoPanel.Name = "LogoPanel";
            this.LogoPanel.Size = new System.Drawing.Size(405, 69);
            this.LogoPanel.TabIndex = 5;
            // 
            // LogoPictureBox
            // 
            this.LogoPictureBox.BackgroundImage = global::Sante.EMR.SmartClient.Infrastructure.Library.Properties.Resources.LargeLogo;
            this.LogoPictureBox.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Center;
            this.LogoPictureBox.Location = new System.Drawing.Point(0, 3);
            this.LogoPictureBox.Name = "LogoPictureBox";
            this.LogoPictureBox.Size = new System.Drawing.Size(304, 63);
            this.LogoPictureBox.TabIndex = 0;
            this.LogoPictureBox.TabStop = false;
            // 
            // button1
            // 
            this.button1.Location = new System.Drawing.Point(159, 210);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(75, 23);
            this.button1.TabIndex = 7;
            this.button1.Text = "Login";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // UserSelectionForm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(392, 255);
            this.Controls.Add(this.button1);
            this.Controls.Add(this.LogoPanel);
            this.Controls.Add(this._messageLabel);
            this.Controls.Add(this.groupBox1);
            this.Name = "UserSelectionForm";
            this.Text = "Login";
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            this.LogoPanel.ResumeLayout(false);
            ((System.ComponentModel.ISupportInitialize)(this.LogoPictureBox)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private TextBox UserNameTextEdit;
        private TextBox PasswordTextEdit;
        private Label UserNamelabel;
        private Label Passwordlabel;
        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.Label _messageLabel;
        private System.Windows.Forms.Panel LogoPanel;
        private System.Windows.Forms.PictureBox LogoPictureBox;
        private Button button1;
    }
}