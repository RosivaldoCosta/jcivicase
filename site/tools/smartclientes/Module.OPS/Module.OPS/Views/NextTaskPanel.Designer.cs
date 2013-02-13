namespace Sante.EMR.SmartClient.Module.OPS.Views
{
    partial class NextTaskPanel
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

        #region Component Designer generated code

        /// <summary> 
        /// Required method for Designer support - do not modify 
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            this.NextTaskdataGridView = new System.Windows.Forms.DataGridView();
            this.Status = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.nextTaskBindingSource = new System.Windows.Forms.BindingSource(this.components);
            ((System.ComponentModel.ISupportInitialize)(this.NextTaskdataGridView)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.nextTaskBindingSource)).BeginInit();
            this.SuspendLayout();
            // 
            // NextTaskdataGridView
            // 
            this.NextTaskdataGridView.AllowUserToAddRows = false;
            this.NextTaskdataGridView.AllowUserToDeleteRows = false;
            this.NextTaskdataGridView.AutoGenerateColumns = false;
            this.NextTaskdataGridView.AutoSizeColumnsMode = System.Windows.Forms.DataGridViewAutoSizeColumnsMode.Fill;
            this.NextTaskdataGridView.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.NextTaskdataGridView.Columns.AddRange(new System.Windows.Forms.DataGridViewColumn[] {
            this.Status});
            this.NextTaskdataGridView.DataSource = this.nextTaskBindingSource;
            this.NextTaskdataGridView.Dock = System.Windows.Forms.DockStyle.Fill;
            this.NextTaskdataGridView.Location = new System.Drawing.Point(0, 0);
            this.NextTaskdataGridView.MultiSelect = false;
            this.NextTaskdataGridView.Name = "NextTaskdataGridView";
            this.NextTaskdataGridView.ReadOnly = true;
            this.NextTaskdataGridView.RowHeadersVisible = false;
            this.NextTaskdataGridView.SelectionMode = System.Windows.Forms.DataGridViewSelectionMode.FullRowSelect;
            this.NextTaskdataGridView.Size = new System.Drawing.Size(440, 150);
            this.NextTaskdataGridView.TabIndex = 0;
            // 
            // Status
            // 
            this.Status.DataPropertyName = "Status";
            this.Status.HeaderText = "Status";
            this.Status.Name = "Status";
            this.Status.ReadOnly = true;
            // 
            // NextTaskPanel
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.Controls.Add(this.NextTaskdataGridView);
            this.Name = "NextTaskPanel";
            this.Size = new System.Drawing.Size(440, 150);
            ((System.ComponentModel.ISupportInitialize)(this.NextTaskdataGridView)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.nextTaskBindingSource)).EndInit();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.DataGridView NextTaskdataGridView;
        private System.Windows.Forms.BindingSource nextTaskBindingSource;
        private System.Windows.Forms.DataGridViewTextBoxColumn dateCompletedDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn Status;
        private System.Windows.Forms.DataGridViewTextBoxColumn TaskName;
        private System.Windows.Forms.DataGridViewTextBoxColumn initialDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn dateDueDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn setDateDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn commentsDataGridViewTextBoxColumn;
    }
}
