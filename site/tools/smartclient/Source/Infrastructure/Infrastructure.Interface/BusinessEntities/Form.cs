using System;
using System.Collections.Generic;
using System.Collections;
using System.Text;
using System.Windows.Forms;
using System.Reflection;
using HttpServer;
using System.Web;
using Sante.EMR.SmartClient.Infrastructure.Interface.Properties;

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
        private string _subject = "";
        private Hashtable _customfieldMap = new Hashtable();
        
        private void populateHash()
        {
            Properties.Settings.Default.Save();
            if (Properties.Settings.Default.Properties.Count > 0)
            {
                string fieldMap = this.FormName + "FieldMap";

                String[] nvpairs;

                switch (FormName)
                {
                    case "Intake Form":
                        nvpairs = Properties.Settings.Default.IntakeFieldMap.Split('&');
                        break;
                    case "Assessment And Treatment":
                        nvpairs = Properties.Settings.Default.MCTAssessmentAndTreatmentFieldMap.Split('&');
                        break;
                    case "Progress Notes":
                        nvpairs = Properties.Settings.Default.ProgressNoteFieldMap.Split('&');
                        break;
                    case "Lethality":
                        nvpairs = Properties.Settings.Default.LethalityFieldMap.Split('&');
                        break;
                    case "MCT Dispatch":
                        nvpairs = Properties.Settings.Default.MCTDispatchFieldMap.Split('&');
                        break;
                    case "Medeval":
                        nvpairs = Properties.Settings.Default.medevalFieldMap.Split('&');
                        break;
                    case "Consent For Services":
                        nvpairs = Properties.Settings.Default.ConsentForServicesFieldMap.Split('&');
                        break;
                    case "Telephone Contact":
                        nvpairs = Properties.Settings.Default.TelephoneFieldMap.Split('&');
                        break;
                    default:
                        nvpairs = null;
                        break;
                }

                if (nvpairs != null)
                {
                    foreach (String pair in nvpairs)
                    {
                        String[] kv = pair.Split('=');

                        if (kv.Length > 1)
                            _customfieldMap[kv[0]] = kv[1];
                    }
                }
            }
        }

        public override string ToString()
        {

            populateHash();

            String t = "";
            foreach (PropertyInfo source in this.GetType().GetProperties())
            {
                try
                {
                foreach (String key in _customfieldMap.Keys)
                {
                    if (key.Equals(source.Name))
                    {
                        string field = _customfieldMap[key] as string;
                    
                        //object o = source.GetValue(value, null);
                        object v = source.GetValue(this, null);
                        if (v != null)
                        {
                            string value;

                            try
                            {

                                value = (string)v;
                                
                            } catch (InvalidCastException e)
                            {
                                try
                                {
                                    value = Convert.ToString((int)v);
                                } catch (InvalidCastException d)
                                {
                                    //DateTime datetype = (DateTime)v;

                                    value = convertToMySQLDate((DateTime)v);
                                }
                            }

                            if (value != "")
                                t += "&" + field + "=" + HttpUtility.UrlEncode(value);
                            
                        }
                    }
                }

                            }
                catch (Exception e)
                {
                    int i = 0;
                    string ex = e.ToString();                    
                }
            }

            return t;
        }

        public static string convertToMySQLDate(DateTime dateTime)
        {
            String date = dateTime.Date.Year.ToString();
            int m = dateTime.Date.Month;
            if (m < 10)
                date += "0" + m.ToString();
            else
               date += m.ToString();

            if (dateTime.Date.Day < 10)
                date += "0" + dateTime.Date.Day.ToString();
            else
                date += dateTime.Date.Day.ToString();

            return date;
        }
         
        public string Subject
        {
            get { return _subject; }
            set { _subject = value; }
        }

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
                if (_name != null && _name != "")
                {

                    return _name;

                }
                else
                {
                    return this.GetType().Name; //_name;
                }
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
                                SetProperty(n.Value.ToString(), n.Name);
                            }

                            if (c is DateTimePicker)
                            {
                                DateTimePicker dt = (DateTimePicker)c;
                                SetProperty(dt.Text, dt.Name);
                            }


                            if (c is ComboBox)
                            {
                                ComboBox cb = ((ComboBox)c);
                                if (!String.IsNullOrEmpty(cb.Text))
                                    SetProperty(cb.Text, cb.Name);
                            }


                            if (c is CheckBox)
                            {
                                CheckBox ch = ((CheckBox)c);
                                if (ch.Checked)
                                    SetProperty("Y", ch.Name);
                                else
                                    SetProperty("N", ch.Name);

                            }

                            if (c is CheckedListBox)
                            {
                                CheckedListBox cb = ((CheckedListBox)c);
                                StringBuilder values = new StringBuilder();
                                string CorrectString;

                                foreach (string i in cb.CheckedItems)
                                {
                                    if (i == "MCT to follow-up")
                                    {   
                                        CorrectString="mct_follow_up";
                                    }
                                    else if (i == "Client refused or unavailable")
                                    {
                                        CorrectString="client_refused_unavailable";
                                    }
                                    else if (i == "Stable upon MCT arrival")
                                    {
                                        CorrectString="stable";
                                    }
                                    else if (i == "Death Other Than Suicide")
                                    {
                                        CorrectString="death_other_suicide";
                                    }
                                    else if (i == "Stable with support")
                                    {
                                        CorrectString="stable_w_support";
                                    }
                                    else if (i == "With Police")
                                    {
                                        CorrectString="w_police";
                                    }
                                    else if (i == "Client was intoxicated upon arrival")
                                    {
                                        CorrectString="intoxicated";
                                    }
                                    else if (i == "Client in treatment currently in the community")
                                    {
                                        CorrectString="in_treatment";
                                    }
                                    else
                                    {
                                        CorrectString = i.Replace(".", "");
                                        CorrectString = CorrectString.Replace("/", "_");
                                        CorrectString = CorrectString.Replace(" ", "_");
                                        CorrectString = CorrectString.Replace("(", "");
                                        CorrectString = CorrectString.Replace(")", "");
                                        CorrectString = CorrectString.Replace(",", "");
                                        CorrectString = CorrectString.ToLower();                                        
                                    }
                                    values.Append(CorrectString);       
                                }

                                SetProperty(values.ToString(), cb.Name);

                            }

                            if (c is TextBox)
                            {
                                try
                                {
                                    TextBox tx = ((TextBox)c);

                                    if (!String.IsNullOrEmpty(tx.Text))
                                    {
                                        SetProperty(tx.Text, tx.Name);


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
                                        SetProperty(name[1], name[0]);
                                    }
                                }
                            }
                        }
                    }

        }

        private void SetProperty(string value, string name)
        {
            PropertyInfo target = this.GetType().GetProperty(name);
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
            }
        }
        public void SetFormFields(HttpForm form)
        {

            foreach (HttpInputItem i in form)
            {
                SetProperty( i.Value,i.Name);
            }
        }

    }
}
