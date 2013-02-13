
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

namespace Sante.EMR.SmartClient.OutBox
{
    partial class OutBoxView
    {
        /// <summary>
        /// The presenter used by this view.
        /// </summary>
        private Sante.EMR.SmartClient.OutBox.OutBoxViewPresenter _presenter = null;

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
            this.components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(OutBoxView));
            this.toolStrip1 = new System.Windows.Forms.ToolStrip();
            this.toolStripButton1 = new System.Windows.Forms.ToolStripButton();
            this.OutboxDataGridView = new System.Windows.Forms.DataGridView();
            this.CaseNumber = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.Depart = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.nameDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.createdDateDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.isSentDataGridViewCheckBoxColumn = new System.Windows.Forms.DataGridViewCheckBoxColumn();
            this.formBindingSource = new System.Windows.Forms.BindingSource(this.components);
            this.SendAlltoolStripButton = new System.Windows.Forms.ToolStripButton();
            this.EditToolStripButton = new System.Windows.Forms.ToolStripButton();
            this.toolStrip1.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.OutboxDataGridView)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.formBindingSource)).BeginInit();
            this.SuspendLayout();
            // 
            // toolStrip1
            // 
            this.toolStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.toolStripButton1,
            this.SendAlltoolStripButton,
            this.EditToolStripButton});
            this.toolStrip1.Location = new System.Drawing.Point(0, 0);
            this.toolStrip1.Name = "toolStrip1";
            this.toolStrip1.Size = new System.Drawing.Size(422, 25);
            this.toolStrip1.TabIndex = 0;
            this.toolStrip1.Text = "toolStrip1";
            // 
            // toolStripButton1
            // 
            this.toolStripButton1.Image = ((System.Drawing.Image)(resources.GetObject("toolStripButton1.Image")));
            this.toolStripButton1.ImageTransparentColor = System.Drawing.Color.Magenta;
            this.toolStripButton1.Name = "toolStripButton1";
            this.toolStripButton1.Size = new System.Drawing.Size(52, 22);
            this.toolStripButton1.Text = "Send";
            // 
            // OutboxDataGridView
            // 
            this.OutboxDataGridView.AllowUserToAddRows = false;
            this.OutboxDataGridView.AllowUserToDeleteRows = false;
            this.OutboxDataGridView.AutoGenerateColumns = false;
            this.OutboxDataGridView.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.OutboxDataGridView.Columns.AddRange(new System.Windows.Forms.DataGridViewColumn[] {
            this.CaseNumber,
            this.nameDataGridViewTextBoxColumn,
            this.Depart,
            this.createdDateDataGridViewTextBoxColumn,
            this.isSentDataGridViewCheckBoxColumn});
            this.OutboxDataGridView.DataSource = this.formBindingSource;
            this.OutboxDataGridView.Dock = System.Windows.Forms.DockStyle.Fill;
            this.OutboxDataGridView.Location = new System.Drawing.Point(0, 25);
            this.OutboxDataGridView.Name = "OutboxDataGridView";
            this.OutboxDataGridView.ReadOnly = true;
            this.OutboxDataGridView.RowHeadersVisible = false;
            this.OutboxDataGridView.Size = new System.Drawing.Size(422, 253);
            this.OutboxDataGridView.TabIndex = 1;
            // 
            // CaseNumber
            // 
            this.CaseNumber.DataPropertyName = "CaseNumber";
            this.CaseNumber.HeaderText = "Case Number";
            this.CaseNumber.Name = "CaseNumber";
            this.CaseNumber.ReadOnly = true;
            // 
            // Depart
            // 
            this.Depart.DataPropertyName = "Depart";
            this.Depart.HeaderText = "Department";
            this.Depart.Name = "Depart";
            this.Depart.ReadOnly = true;
            // 
            // nameDataGridViewTextBoxColumn
            // 
            this.nameDataGridViewTextBoxColumn.DataPropertyName = "Name";
            this.nameDataGridViewTextBoxColumn.HeaderText = "Form Name";
            this.nameDataGridViewTextBoxColumn.Name = "nameDataGridViewTextBoxColumn";
            this.nameDataGridViewTextBoxColumn.ReadOnly = true;
            // 
            // createdDateDataGridViewTextBoxColumn
            // 
            this.createdDateDataGridViewTextBoxColumn.DataPropertyName = "CreatedDate";
            this.createdDateDataGridViewTextBoxColumn.HeaderText = "Date Created";
            this.createdDateDataGridViewTextBoxColumn.Name = "createdDateDataGridViewTextBoxColumn";
            this.createdDateDataGridViewTextBoxColumn.ReadOnly = true;
            // 
            // isSentDataGridViewCheckBoxColumn
            // 
            this.isSentDataGridViewCheckBoxColumn.DataPropertyName = "isSent";
            this.isSentDataGridViewCheckBoxColumn.HeaderText = "Submitted";
            this.isSentDataGridViewCheckBoxColumn.Name = "isSentDataGridViewCheckBoxColumn";
            this.isSentDataGridViewCheckBoxColumn.ReadOnly = true;
            // 
            // formBindingSource
            // 
            this.formBindingSource.DataSource = typeof(Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.Form);
            // 
            // SendAlltoolStripButton
            // 
            this.SendAlltoolStripButton.Image = ((System.Drawing.Image)(resources.GetObject("SendAlltoolStripButton.Image")));
            this.SendAlltoolStripButton.ImageTransparentColor = System.Drawing.Color.Magenta;
            this.SendAlltoolStripButton.Name = "SendAlltoolStripButton";
            this.SendAlltoolStripButton.Size = new System.Drawing.Size(66, 22);
            this.SendAlltoolStripButton.Text = "Send All";
            // 
            // EditToolStripButton
            // 
            this.EditToolStripButton.Image = ((System.Drawing.Image)(resources.GetObject("EditToolStripButton.Image")));
            this.EditToolStripButton.ImageTransparentColor = System.Drawing.Color.Magenta;
            this.EditToolStripButton.Name = "EditToolStripButton";
            this.EditToolStripButton.Size = new System.Drawing.Size(45, 22);
            this.EditToolStripButton.Text = "Edit";
            // 
            // OutBoxView
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.Controls.Add(this.OutboxDataGridView);
            this.Controls.Add(this.toolStrip1);
            this.Name = "OutBoxView";
            this.Size = new System.Drawing.Size(422, 278);
            this.toolStrip1.ResumeLayout(false);
            this.toolStrip1.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)(this.OutboxDataGridView)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.formBindingSource)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.ToolStrip toolStrip1;
        private System.Windows.Forms.ToolStripButton toolStripButton1;
        private System.Windows.Forms.DataGridView OutboxDataGridView;
        private System.Windows.Forms.BindingSource formBindingSource;
        private System.Windows.Forms.DataGridViewTextBoxColumn CaseNumber;
        private System.Windows.Forms.DataGridViewTextBoxColumn nameDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn Depart;
        private System.Windows.Forms.DataGridViewTextBoxColumn createdDateDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewCheckBoxColumn isSentDataGridViewCheckBoxColumn;
        private System.Windows.Forms.ToolStripButton SendAlltoolStripButton;
        private System.Windows.Forms.ToolStripButton EditToolStripButton;
    }
}

