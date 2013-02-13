using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;
using System.Reflection;
using HttpServer;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class Form 
    {
        
        private string _name;
        private string _depart;
        private int _caseNumber;
        private bool _isSent = false;
        private int _employeeID;
        private int _patientID;
        private DateTime _cstamp;
        private DateTime _tstamp;
        
            
        public int CaseNumber
        {
            get
            {
                return _caseNumber;
            }

            set
            {
            	_caseNumber = value;
            }
        }


        public int EmployeeID
        {
            get
            {
                return _employeeID;
            }
            set
            {
                _employeeID = value;
            }
        }
        public DateTime tstamp
        {
            get
            {
                return _tstamp;
            }

            set
            {
                _tstamp = value;
            }
        }

        public string Depart
        {
            get
            {
                return _depart;
            }

            set
            {
            	_depart = value;
            }
        }

        public string FormName
        {
            get
            {
                return _name;
            }

            set
            {
            	_name = value;
            }
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="controlCollection"></param>
        public void SetFormFields(System.Windows.Forms.Control.ControlCollection controlCollection)
        {
            

                if (controlCollection != null)
                    if (controlCollection.Count > 0)
                    {
                        foreach (Control c in controlCollection)
                        {
                            if (!(c is TextBox) &&
                                !(c is RadioButton) &&
                                !(c is CheckBox) &&
                                !(c is CheckedListBox) &&
                                !(c is ComboBox) &&
                                !(c is DateTimePicker) &&
                                !(c is NumericUpDown))
                                SetFormFields(c.Controls);

                            if (c is NumericUpDown)
                            {
                                NumericUpDown n = (NumericUpDown)c;
                                SetProperty(n.Value.ToString(), n.Name, false);
                            }

                            if (c is DateTimePicker)
                            {
                                DateTimePicker dt = (DateTimePicker)c;
                                SetProperty(dt.Text, dt.Name, false);
                            }


                            if (c is ComboBox)
                            {
                                ComboBox cb = ((ComboBox)c);
                                if (!String.IsNullOrEmpty(cb.Text))
                                    SetProperty(cb.Text, cb.Name,false);
                            }


                            if (c is CheckBox)
                            {
                                CheckBox ch = ((CheckBox)c);
                                if (ch.Checked)
                                    SetProperty("Y", ch.Name,false);
                                else
                                    SetProperty("N", ch.Name,false);

                            }

                            if (c is CheckedListBox)
                            {
                                CheckedListBox cb = ((CheckedListBox)c);
                                StringBuilder values = new StringBuilder();

                                foreach (string i in cb.SelectedItems)
                                {
                                    values.Append(i);
                                    values.Append(",");
                                }

                                SetProperty(values.ToString(), cb.Name,false);

                            }

                            if (c is TextBox)
                            {
                                try
                                {
                                    TextBox tx = ((TextBox)c);

                                    if (!String.IsNullOrEmpty(tx.Text))
                                    {
                                        SetProperty(tx.Text, tx.Name,false);


                                    }
                                }
                                catch (NullReferenceException ex)
                                {


                                }
                                catch (Exception e)
                                {
                                    throw e;

                                }
                            }
                            else if (c is RadioButton)
                            {
                                if (((RadioButton)c).Checked)
                                {
                                    string[] name = c.Name.Split('_');
                                    if (name.Length == 2)
                                    {
                                        SetProperty(name[1], name[0],false);
                                    }
                                }
                            }
                        }
                    }

        }

        private void SetProperty(string value, string name, bool isBase)
        {
            PropertyInfo target;

            if (isBase)
                target = this.GetType().BaseType.GetProperty(name);
            else
                target = this.GetType().GetProperty(name);
            
            if (target != null)
            {
                MethodInfo targetSetMethod = target.GetSetMethod();

                if (targetSetMethod != null)
                {
                    if (!String.IsNullOrEmpty(value))
                    {
                        try
                        {
                            targetSetMethod.Invoke(this, new object[] { value });
                        }
                        catch (ArgumentException ex)
                        {

                            try
                            {
                                targetSetMethod.Invoke(this, new object[] { Convert.ToInt32(value) });
                            }
                            catch (ArgumentException aex)
                            {
                                throw aex;
                            }
                            catch (FormatException fe)
                            {
                                try
                                {
                                    targetSetMethod.Invoke(this, new object[] { Convert.ToDateTime(value) });
                                }
                                catch (ArgumentException aex)
                                {
                                    throw aex;
                                }
                                catch (FormatException fex)
                                {
                                    throw fex;
                                }
                            }
                        }
                    }
                }
                else
                {
                    SetProperty(value, name, true);
                }
            }
        }
        public void SetFormFields(HttpForm form)
        {

            foreach (HttpInputItem i in form)
            {
                SetProperty( i.Value,i.Name,false);
            }
        }

    }
}
