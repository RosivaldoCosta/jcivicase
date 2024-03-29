
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

namespace Sante.EMR.SmartClient.Module.OPS
{
    partial class OPSView
    {
        /// <summary>
        /// The presenter used by this view.
        /// </summary>
        //private Sante.EMR.SmartClient.Module.OPS.OPSCaseQueueViewPresenter _presenter = null;

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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(OPSView));
            this.Statuslabel = new System.Windows.Forms.Label();
            this.dataGridView = new System.Windows.Forms.DataGridView();
            this.caseQueueBindingSource = new System.Windows.Forms.BindingSource(this.components);
            this.toolStrip1 = new System.Windows.Forms.ToolStrip();
            this.NewCaseButton = new System.Windows.Forms.ToolStripButton();
            this.SearchButton = new System.Windows.Forms.ToolStripButton();
            this.StatusFilter = new System.Windows.Forms.ComboBox();
            this.splitContainer = new System.Windows.Forms.SplitContainer();
            this.CaseHistoryTabControl = new System.Windows.Forms.TabControl();
            this.NextTaskTab = new System.Windows.Forms.TabPage();
            this.nextTaskPanel1 = new Sante.EMR.SmartClient.Module.OPS.Views.NextTaskPanel();
            this.OPSTab = new System.Windows.Forms.TabPage();
            this.MCTTab = new System.Windows.Forms.TabPage();
            this.IHITTab = new System.Windows.Forms.TabPage();
            this.UCCTab = new System.Windows.Forms.TabPage();
            this.CISMTab = new System.Windows.Forms.TabPage();
            this.VouchersTab = new System.Windows.Forms.TabPage();
            this.PhoneContactTab = new System.Windows.Forms.TabPage();
            this.CaseHistoryTab = new System.Windows.Forms.TabPage();
            this.caseBindingSource = new System.Windows.Forms.BindingSource(this.components);
            ((System.ComponentModel.ISupportInitialize)(this.dataGridView)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.caseQueueBindingSource)).BeginInit();
            this.toolStrip1.SuspendLayout();
            this.splitContainer.Panel1.SuspendLayout();
            this.splitContainer.SuspendLayout();
            this.NextTaskTab.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.caseBindingSource)).BeginInit();
            this.SuspendLayout();
            // 
            // Statuslabel
            // 
            this.Statuslabel.AutoSize = true;
            this.Statuslabel.Location = new System.Drawing.Point(3, 7);
            this.Statuslabel.Name = "Statuslabel";
            this.Statuslabel.Size = new System.Drawing.Size(40, 13);
            this.Statuslabel.TabIndex = 0;
            this.Statuslabel.Text = "Status:";
            // 
            // dataGridView
            // 
            this.dataGridView.AllowUserToAddRows = false;
            this.dataGridView.AllowUserToDeleteRows = false;
            this.dataGridView.AutoGenerateColumns = false;
            this.dataGridView.AutoSizeColumnsMode = System.Windows.Forms.DataGridViewAutoSizeColumnsMode.Fill;
            this.dataGridView.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.dataGridView.DataSource = this.caseQueueBindingSource;
            this.dataGridView.Dock = System.Windows.Forms.DockStyle.Fill;
            this.dataGridView.Location = new System.Drawing.Point(0, 0);
            this.dataGridView.MultiSelect = false;
            this.dataGridView.Name = "dataGridView";
            this.dataGridView.ReadOnly = true;
            this.dataGridView.RowHeadersVisible = false;
            this.dataGridView.SelectionMode = System.Windows.Forms.DataGridViewSelectionMode.FullRowSelect;
            this.dataGridView.Size = new System.Drawing.Size(419, 170);
            this.dataGridView.TabIndex = 2;
            // 
            // toolStrip1
            // 
            this.toolStrip1.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.toolStrip1.GripStyle = System.Windows.Forms.ToolStripGripStyle.Hidden;
            this.toolStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.NewCaseButton,
            this.SearchButton});
            this.toolStrip1.Location = new System.Drawing.Point(0, 253);
            this.toolStrip1.Name = "toolStrip1";
            this.toolStrip1.RenderMode = System.Windows.Forms.ToolStripRenderMode.System;
            this.toolStrip1.RightToLeft = System.Windows.Forms.RightToLeft.Yes;
            this.toolStrip1.Size = new System.Drawing.Size(422, 25);
            this.toolStrip1.TabIndex = 3;
            this.toolStrip1.Text = "toolStrip1";
            // 
            // NewCaseButton
            // 
            this.NewCaseButton.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Text;
            this.NewCaseButton.Image = ((System.Drawing.Image)(resources.GetObject("NewCaseButton.Image")));
            this.NewCaseButton.ImageTransparentColor = System.Drawing.Color.Magenta;
            this.NewCaseButton.Name = "NewCaseButton";
            this.NewCaseButton.Size = new System.Drawing.Size(60, 22);
            this.NewCaseButton.Text = "New Case";
            this.NewCaseButton.Click += new System.EventHandler(this.NewCaseButton_Click);
            // 
            // SearchButton
            // 
            this.SearchButton.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Text;
            this.SearchButton.Image = ((System.Drawing.Image)(resources.GetObject("SearchButton.Image")));
            this.SearchButton.ImageTransparentColor = System.Drawing.Color.Magenta;
            this.SearchButton.Name = "SearchButton";
            this.SearchButton.Size = new System.Drawing.Size(45, 22);
            this.SearchButton.Text = "Search";
            this.SearchButton.Click += new System.EventHandler(this.SearchButton_Click);
            // 
            // StatusFilter
            // 
            this.StatusFilter.FormattingEnabled = true;
            this.StatusFilter.Items.AddRange(new object[] {
            "All",
            "Active",
            "Unresolved",
            "Follow Ups",
            "East Side IHIT",
            "West Side IHIT",
            "MCT",
            "Frequent Caller"});
            this.StatusFilter.Location = new System.Drawing.Point(49, 3);
            this.StatusFilter.Name = "StatusFilter";
            this.StatusFilter.Size = new System.Drawing.Size(121, 21);
            this.StatusFilter.TabIndex = 4;
            // 
            // splitContainer
            // 
            this.splitContainer.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.splitContainer.Location = new System.Drawing.Point(3, 30);
            this.splitContainer.Name = "splitContainer";
            this.splitContainer.Orientation = System.Windows.Forms.Orientation.Horizontal;
            // 
            // splitContainer.Panel1
            // 
            this.splitContainer.Panel1.Controls.Add(this.dataGridView);
            this.splitContainer.Size = new System.Drawing.Size(419, 220);
            this.splitContainer.SplitterDistance = 170;
            this.splitContainer.TabIndex = 5;
            // 
            // CaseHistoryTabControl
            // 
            this.CaseHistoryTabControl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.CaseHistoryTabControl.Location = new System.Drawing.Point(0, 0);
            this.CaseHistoryTabControl.Name = "CaseHistoryTabControl";
            this.CaseHistoryTabControl.SelectedIndex = 0;
            this.CaseHistoryTabControl.Size = new System.Drawing.Size(419, 46);
            this.CaseHistoryTabControl.TabIndex = 0;
            // 
            // NextTaskTab
            // 
            this.NextTaskTab.Controls.Add(this.nextTaskPanel1);
            this.NextTaskTab.Location = new System.Drawing.Point(4, 22);
            this.NextTaskTab.Name = "NextTaskTab";
            this.NextTaskTab.Padding = new System.Windows.Forms.Padding(3);
            this.NextTaskTab.Size = new System.Drawing.Size(411, 20);
            this.NextTaskTab.TabIndex = 0;
            this.NextTaskTab.Text = "Next Task";
            this.NextTaskTab.UseVisualStyleBackColor = true;
            // 
            // nextTaskPanel1
            // 
            this.nextTaskPanel1.AutoSize = true;
            this.nextTaskPanel1.Dock = System.Windows.Forms.DockStyle.Fill;
            this.nextTaskPanel1.Location = new System.Drawing.Point(3, 3);
            this.nextTaskPanel1.Name = "nextTaskPanel1";
            this.nextTaskPanel1.Size = new System.Drawing.Size(405, 14);
            this.nextTaskPanel1.TabIndex = 0;
            // 
            // OPSTab
            // 
            this.OPSTab.Location = new System.Drawing.Point(4, 22);
            this.OPSTab.Name = "OPSTab";
            this.OPSTab.Padding = new System.Windows.Forms.Padding(3);
            this.OPSTab.Size = new System.Drawing.Size(411, 20);
            this.OPSTab.TabIndex = 1;
            this.OPSTab.Text = "OPS";
            this.OPSTab.UseVisualStyleBackColor = true;
            // 
            // MCTTab
            // 
            this.MCTTab.Location = new System.Drawing.Point(4, 22);
            this.MCTTab.Name = "MCTTab";
            this.MCTTab.Size = new System.Drawing.Size(411, 20);
            this.MCTTab.TabIndex = 2;
            this.MCTTab.Text = "MCT";
            this.MCTTab.UseVisualStyleBackColor = true;
            // 
            // IHITTab
            // 
            this.IHITTab.Location = new System.Drawing.Point(4, 22);
            this.IHITTab.Name = "IHITTab";
            this.IHITTab.Size = new System.Drawing.Size(411, 20);
            this.IHITTab.TabIndex = 3;
            this.IHITTab.Text = "IHIT";
            this.IHITTab.UseVisualStyleBackColor = true;
            // 
            // UCCTab
            // 
            this.UCCTab.Location = new System.Drawing.Point(4, 22);
            this.UCCTab.Name = "UCCTab";
            this.UCCTab.Size = new System.Drawing.Size(411, 20);
            this.UCCTab.TabIndex = 4;
            this.UCCTab.Text = "UCC";
            this.UCCTab.UseVisualStyleBackColor = true;
            // 
            // CISMTab
            // 
            this.CISMTab.Location = new System.Drawing.Point(4, 22);
            this.CISMTab.Name = "CISMTab";
            this.CISMTab.Size = new System.Drawing.Size(411, 20);
            this.CISMTab.TabIndex = 5;
            this.CISMTab.Text = "CISM";
            this.CISMTab.UseVisualStyleBackColor = true;
            // 
            // VouchersTab
            // 
            this.VouchersTab.Location = new System.Drawing.Point(4, 22);
            this.VouchersTab.Name = "VouchersTab";
            this.VouchersTab.Size = new System.Drawing.Size(411, 20);
            this.VouchersTab.TabIndex = 6;
            this.VouchersTab.Text = "Vouchers";
            this.VouchersTab.UseVisualStyleBackColor = true;
            // 
            // PhoneContactTab
            // 
            this.PhoneContactTab.Location = new System.Drawing.Point(4, 22);
            this.PhoneContactTab.Name = "PhoneContactTab";
            this.PhoneContactTab.Size = new System.Drawing.Size(411, 20);
            this.PhoneContactTab.TabIndex = 7;
            this.PhoneContactTab.Text = "Phone Contact";
            this.PhoneContactTab.UseVisualStyleBackColor = true;
            // 
            // CaseHistoryTab
            // 
            this.CaseHistoryTab.Location = new System.Drawing.Point(4, 22);
            this.CaseHistoryTab.Name = "CaseHistoryTab";
            this.CaseHistoryTab.Size = new System.Drawing.Size(411, 20);
            this.CaseHistoryTab.TabIndex = 8;
            this.CaseHistoryTab.Text = "Case History";
            this.CaseHistoryTab.UseVisualStyleBackColor = true;
            // 
            // OPSView
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.Controls.Add(this.splitContainer);
            this.Controls.Add(this.StatusFilter);
            this.Controls.Add(this.toolStrip1);
            this.Controls.Add(this.Statuslabel);
            this.Name = "OPSView";
            this.Controls.SetChildIndex(this.Statuslabel, 0);
            this.Controls.SetChildIndex(this.toolStrip1, 0);
            this.Controls.SetChildIndex(this.StatusFilter, 0);
            this.Controls.SetChildIndex(this.splitContainer, 0);
            ((System.ComponentModel.ISupportInitialize)(this.dataGridView)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.caseQueueBindingSource)).EndInit();
            this.toolStrip1.ResumeLayout(false);
            this.toolStrip1.PerformLayout();
            this.splitContainer.Panel1.ResumeLayout(false);
            this.splitContainer.ResumeLayout(false);
            this.NextTaskTab.ResumeLayout(false);
            this.NextTaskTab.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)(this.caseBindingSource)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Label Statuslabel;
        private System.Windows.Forms.BindingSource caseBindingSource;
        private System.Windows.Forms.DataGridView dataGridView;
        private System.Windows.Forms.BindingSource caseQueueBindingSource;
        private System.Windows.Forms.ToolStrip toolStrip1;
        private System.Windows.Forms.ToolStripButton NewCaseButton;
        private System.Windows.Forms.ToolStripButton SearchButton;
        private System.Windows.Forms.ComboBox StatusFilter;
        private System.Windows.Forms.SplitContainer splitContainer;
        private System.Windows.Forms.TabControl CaseHistoryTabControl;
        private System.Windows.Forms.TabPage NextTaskTab;
        private System.Windows.Forms.TabPage OPSTab;
        private System.Windows.Forms.TabPage MCTTab;
        private System.Windows.Forms.TabPage IHITTab;
        private System.Windows.Forms.TabPage UCCTab;
        private System.Windows.Forms.TabPage CISMTab;
        private System.Windows.Forms.TabPage VouchersTab;
        private System.Windows.Forms.TabPage PhoneContactTab;
        private System.Windows.Forms.TabPage CaseHistoryTab;
        private System.Windows.Forms.DataGridViewTextBoxColumn caseNumberDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn nextTaskDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn nextTaskDateDataGridViewTextBoxColumn;
        private Sante.EMR.SmartClient.Module.OPS.Views.NextTaskPanel nextTaskPanel1;
    }
}

