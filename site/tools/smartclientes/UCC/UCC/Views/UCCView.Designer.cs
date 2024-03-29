
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

namespace Sante.EMR.SmartClient.UCC
{
    partial class UCCView
    {
        /// <summary>
        /// The presenter used by this view.
        /// </summary>
        private Sante.EMR.SmartClient.UCC.UCCViewPresenter _presenter = null;

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
            if (disposing)
            {
                if (_presenter != null)
                    _presenter.Dispose();

                if (components != null)
                    components.Dispose();
            }

            base.Dispose(disposing);
        }

        #region Component Designer generated code

        /// <summary> 
        /// Required method for Designer support - do not modify 
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.UCCFormsMenuGroupBox = new System.Windows.Forms.GroupBox();
            this.NotesButton = new System.Windows.Forms.Button();
            this.MedicalEvalButton = new System.Windows.Forms.Button();
            this.AssesTreatmentButton = new System.Windows.Forms.Button();
            this.UCCFormsMenuGroupBox.SuspendLayout();
            this.SuspendLayout();
            // 
            // UCCFormsMenuGroupBox
            // 
            this.UCCFormsMenuGroupBox.Controls.Add(this.NotesButton);
            this.UCCFormsMenuGroupBox.Controls.Add(this.MedicalEvalButton);
            this.UCCFormsMenuGroupBox.Controls.Add(this.AssesTreatmentButton);
            this.UCCFormsMenuGroupBox.Dock = System.Windows.Forms.DockStyle.Fill;
            this.UCCFormsMenuGroupBox.Location = new System.Drawing.Point(0, 0);
            this.UCCFormsMenuGroupBox.Name = "UCCFormsMenuGroupBox";
            this.UCCFormsMenuGroupBox.Size = new System.Drawing.Size(500, 278);
            this.UCCFormsMenuGroupBox.TabIndex = 0;
            this.UCCFormsMenuGroupBox.TabStop = false;
            this.UCCFormsMenuGroupBox.Text = "UCC Forms Menu";
            // 
            // NotesButton
            // 
            this.NotesButton.Location = new System.Drawing.Point(52, 172);
            this.NotesButton.Name = "NotesButton";
            this.NotesButton.Size = new System.Drawing.Size(200, 50);
            this.NotesButton.TabIndex = 22;
            this.NotesButton.Text = "Notes";
            this.NotesButton.UseVisualStyleBackColor = true;
            this.NotesButton.Click += new System.EventHandler(this.LethalityButton_Click);
            // 
            // MedicalEvalButton
            // 
            this.MedicalEvalButton.Location = new System.Drawing.Point(52, 72);
            this.MedicalEvalButton.Name = "MedicalEvalButton";
            this.MedicalEvalButton.Size = new System.Drawing.Size(200, 50);
            this.MedicalEvalButton.TabIndex = 20;
            this.MedicalEvalButton.Text = "Medical Evaluation";
            this.MedicalEvalButton.UseVisualStyleBackColor = true;
            this.MedicalEvalButton.Click += new System.EventHandler(this.MCTDispatchButton_Click);
            // 
            // AssesTreatmentButton
            // 
            this.AssesTreatmentButton.Location = new System.Drawing.Point(52, 23);
            this.AssesTreatmentButton.Name = "AssesTreatmentButton";
            this.AssesTreatmentButton.Size = new System.Drawing.Size(200, 50);
            this.AssesTreatmentButton.TabIndex = 19;
            this.AssesTreatmentButton.Text = "Assessment and Treatment";
            this.AssesTreatmentButton.UseVisualStyleBackColor = true;
            this.AssesTreatmentButton.Click += new System.EventHandler(this.AssesTreatmentButton_Click);
            // 
            // UCCView
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.Controls.Add(this.UCCFormsMenuGroupBox);
            this.Name = "UCCView";
            this.Size = new System.Drawing.Size(500, 278);
            this.Load += new System.EventHandler(this.UCCView_Load);
            this.UCCFormsMenuGroupBox.ResumeLayout(false);
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.GroupBox UCCFormsMenuGroupBox;
        private System.Windows.Forms.Button NotesButton;
        private System.Windows.Forms.Button MedicalEvalButton;
        private System.Windows.Forms.Button AssesTreatmentButton;

    }
}

