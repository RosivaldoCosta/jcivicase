using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;

namespace Sante.EMR.SmartClient.Infrastructure.Data.Services
{

    public interface IDataAccessService
    {
        //CaseQueue RetrieveCaseQueue(DataAccessType type);

        bool SynchronizeData();

       // List<NextTask> GetNextTask(int caseNumber);

        List<Patient> Search(string first, string last, string phone, string address);


        bool SendForm(Form f);

        bool SendCase(Case c);
    }
}
