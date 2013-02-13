using System;
using System.Collections.Generic;
using System.Text;
using System.Data;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    public class CaseQueue : List<Case>
    {

        public CaseQueue() { }
        public CaseQueue(System.Data.DataTable tableAdapter)
        {
            
            
            foreach (DataRow r in tableAdapter.Rows)
            {
                Case c = new Case();

                try
                {
                    if (r.ItemArray.Length > 0)
                    {
                        if (r[0] != null)
                            c.CaseNumber = (int)r[0];

                        if (r[1] != null)
                            c.NextTask = r[1] as string;

                        if (r[2] != null)
                            c.SetDate = (DateTime)r[2];

                        if (r[3] != null)
                            c.NextTaskDate = (DateTime)r[3];

                        if (r[7] != null && !String.IsNullOrEmpty((string)r[7]))
                        {
                            switch ((string)r[7])
                            {
                                case "Active":
                                    c.Status = Status.Active;      
                                    break;
                                case "Unresolved":
                                    c.Status = Status.Unresolved;
                                    break;
                                case "Follow Up":
                                    c.Status = Status.FollowUp;
                                    break;
                                case "East IHIT":
                                    c.Status = Status.EastIHIT;
                                    break;
                                case "Frequent Caller":
                                    c.Status = Status.FrequentCaller;
                                    break;
                                case "MCT":
                                    c.Status = Status.MCT;
                                    break;
                                case "West IHIT Referral":
                                    c.Status = Status.WestIHIT;
                                    break;
                                case "CISM":
                                    c.Status = Status.CISM;
                                    break;
                                case "Recommend for Closure":
                                    c.Status = Status.Closure;
                                    break;
                                default:
                                    c.Status = Status.Active;
                                    break;
                            } 
                        }
                    }
                }
                catch (InvalidCastException ex)
                {

                }

                this.Add(c);
            }
        }

        private int _id;

        //public int Length
        //{
        //    get
        //    {
        //        return this.Length;
        //    }
        //}


        public int Id
        {
            get
            {
                return _id;
            }

            set
            {
            	_id = value;
            }
        }
    }
}
