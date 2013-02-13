using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Library.EntityTranslators;
using BusinessEntitiesAlias = Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using DataWebServiceAlias = Sante.EMR.SmartClient.Infrastructure.Data.DataWebService;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;

namespace Sante.EMR.SmartClient.Infrastructure.Data.EntityTranslators
{
    public class StatusTranslator : EntityMapperTranslator<BusinessEntitiesAlias.Status, DataWebServiceAlias.Status>
    {
        protected override BusinessEntitiesAlias.Status ServiceToBusiness(IEntityTranslatorService service, DataWebServiceAlias.Status value)
        {
            switch (value)
            {
                case DataWebServiceAlias.Status.Active:
                    return BusinessEntitiesAlias.Status.Active;
                    
                case DataWebServiceAlias.Status.Unresolved:
                    return BusinessEntitiesAlias.Status.Unresolved;
                    
                case DataWebServiceAlias.Status.FollowUp:
                    return BusinessEntitiesAlias.Status.FollowUp;
                    
                case DataWebServiceAlias.Status.EastIHIT:
                    return BusinessEntitiesAlias.Status.EastIHIT;
                    
                case DataWebServiceAlias.Status.FrequentCaller:
                    return BusinessEntitiesAlias.Status.FrequentCaller;
                    
                case DataWebServiceAlias.Status.MCT:
                    return BusinessEntitiesAlias.Status.MCT;
                    
                case DataWebServiceAlias.Status.WestIHIT:
                    return BusinessEntitiesAlias.Status.WestIHIT;
                    
                case DataWebServiceAlias.Status.CISM:
                    return BusinessEntitiesAlias.Status.CISM;
                    
                case DataWebServiceAlias.Status.Closure:
                    return BusinessEntitiesAlias.Status.Closure;
                    
                default:
                    return BusinessEntitiesAlias.Status.Active;
                    
            }

            //return c1;
        }

        protected override Sante.EMR.SmartClient.Infrastructure.Data.DataWebService.Status BusinessToService(IEntityTranslatorService service, Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.Status value)
        {
            switch (value)
            {
                case BusinessEntitiesAlias.Status.Active:
                    return DataWebServiceAlias.Status.Active;
                    break;
                case BusinessEntitiesAlias.Status.Unresolved:
                    return DataWebServiceAlias.Status.Unresolved;
                    break;
                case BusinessEntitiesAlias.Status.FollowUp:
                    return DataWebServiceAlias.Status.FollowUp;
                    break;
                case BusinessEntitiesAlias.Status.EastIHIT:
                    return DataWebServiceAlias.Status.EastIHIT;
                    break;
                case BusinessEntitiesAlias.Status.FrequentCaller:
                    return DataWebServiceAlias.Status.FrequentCaller;
                    break;
                case BusinessEntitiesAlias.Status.MCT:
                    return DataWebServiceAlias.Status.MCT;
                    break;
                case BusinessEntitiesAlias.Status.WestIHIT:
                    return DataWebServiceAlias.Status.WestIHIT;
                    break;
                case BusinessEntitiesAlias.Status.CISM:
                    return DataWebServiceAlias.Status.CISM;
                    break;
                case BusinessEntitiesAlias.Status.Closure:
                    return DataWebServiceAlias.Status.Closure;
                    break;
                default:
                    return DataWebServiceAlias.Status.Active;
                    break;
            }

        }
    }
}
