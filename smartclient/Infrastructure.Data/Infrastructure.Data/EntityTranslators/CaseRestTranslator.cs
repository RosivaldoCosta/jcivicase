using System;
using Sante.EMR.SmartClient.Infrastructure.Library.EntityTranslators;
using BusinessEntitiesAlias = Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using DataWebServiceAlias = Sante.EMR.SmartClient.Infrastructure.Data.DataWebService;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;


namespace Sante.EMR.SmartClient.Infrastructure.Data.EntityTranslators
{
    public class CaseRestTranslator : EntityMapperTranslator<BusinessEntitiesAlias.Case, String>
    {
        protected override String BusinessToService(IEntityTranslatorService service, BusinessEntitiesAlias.Case value)
        {
            String v = "";
            v += "caseid="+value.CaseNumber;
            //c.ClosedInitials = value.ClosedInitials;
            //c.Comments = value.Comments;
            //c.DateClosed = value.DateClosed;
            //c.DateOpened = value.DateOpened;
            ////c.Forms = value.Forms;
            //c.NextTask = value.NextTask;
            //c.NextTaskDate = value.NextTaskDate;
            ////c.NextTaskObj = value.NextTaskObj;
            //c.OpenBy = value.OpenBy;
            //c.SetDate = value.SetDate;
            //c.UserGeneratedCaseNumber = value.UserGeneratedCaseNumber;

            //StatusTranslator status = new StatusTranslator();
            //c.Status = status.Translate<DataWebServiceAlias.Status>(service, value.Status);

            ////c.Status = value.Status;
            //c.StatusDate = value.StatusDate;
            //c.Tstamp = value.Tstamp;

            return v;
        }

        protected override BusinessEntitiesAlias.Case ServiceToBusiness(IEntityTranslatorService service, String value)
        {
            BusinessEntitiesAlias.Case c1 = new BusinessEntitiesAlias.Case();
            //c1.CaseNumber = value.CaseNumber;
            //c1.ClosedInitials = value.ClosedInitials;
            //c1.Comments = value.Comments;
            //c1.DateClosed = value.DateClosed;
            //c1.DateOpened = value.DateOpened;
            //c1.NextTask = value.NextTask;
            //c1.NextTaskDate = value.NextTaskDate;
            //c1.OpenBy = value.OpenBy;
            //c1.SetDate = value.SetDate;
            //c1.UserGeneratedCaseNumber = value.UserGeneratedCaseNumber;
            //StatusTranslator status = new StatusTranslator();
            //c1.Status = status.Translate<BusinessEntitiesAlias.Status>(service, value.Status);

            //c1.StatusDate = value.StatusDate;
            //c1.Tstamp = value.Tstamp;

            return c1;
        }
    }
}
