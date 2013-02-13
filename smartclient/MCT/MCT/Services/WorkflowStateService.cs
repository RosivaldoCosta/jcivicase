using System;
using System.Collections.Generic;
using System.Text;
using System.Web.Services.Description;
using Microsoft.Practices.CompositeUI;
namespace Sante.EMR.SmartClient.MCT.Services
{
    public interface IWorkflowStateService
    {
        string NextForm { get; set; }
        int CaseNumber { get; set; }
        string Depart { get; set;}
    }

    [Service(typeof(IWorkflowStateService))]
    public class WorkflowStateService : IWorkflowStateService
    {
        private string _nextForm;
        private int _caseNumber;
        private string _source;

        public string Depart
        {
            get
            {
                return _source;
            }

            set
            {
            	_source = value;
            }
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

        public string NextForm 
        {
            get { return _nextForm; }
            set { _nextForm = value; } 
    
        }
    }
}
