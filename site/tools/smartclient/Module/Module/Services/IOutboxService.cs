using System;
using System.Collections.Generic;
using System.Text;
using System.Collections;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;


namespace Sante.EMR.SmartClient.Module.Services
{
    public interface IOutboxService : IEnumerable<Case>
    {
        void Add(Case form);
        List<Case> GetCases();
        void Clear();
        Case GetCase(int i);
        List<string> GetCaseNumbers();

        bool SendForm(Form _selectedForm);

        event EventHandler UpdateView;
    }
}
